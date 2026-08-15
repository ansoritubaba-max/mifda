# Panduan Auto-Deploy: Local → GitHub → cPanel (via SSH)

SSH ternyata ada di hosting kamu — jadi kita pakai jalur yang paling bersih & standar: **GitHub Actions SSH ke server**, jalanin `git pull` + `composer install` + `migrate` + cache clear otomatis tiap `git push`. Gak perlu buka cPanel lagi sama sekali.

Berlaku buat 2 project: **Absensi** (`File cPanel/`) dan **Mifda** (`aplikasi-belajar-mi 2/`) — repo GitHub terpisah, deploy terpisah, masing-masing punya `.github/workflows/deploy.yml` + `deploy.sh` sendiri (udah aku siapin).

## Penting: ada 2 SSH key yang beda fungsi, jangan ketuker

1. **Key #1 — GitHub Actions → Server** (buat GitHub bisa "masuk" ke server dan jalanin `deploy.sh`). Ini yang PERLU kamu authorize di cPanel SSH Access dan private key-nya disimpan sebagai GitHub Secret.
2. **Key #2 — Server → GitHub** (buat server bisa `git pull` dari repo GitHub yang PRIVATE). Ini dibuat DI SERVER (lewat Terminal cPanel), public key-nya didaftarin sebagai Deploy Key di tiap repo GitHub.

Kalau key yang tadi kamu buat itu buat login/connect ke server (Key #1), itu udah pas — tinggal lanjut dari Bagian 2. Kalau ternyata itu buat hal lain, gak masalah, tinggal ikutin Bagian 2 buat bikin yang bener.

## Bagian 1 — Push ke GitHub (2x, satu per app) — SAMA kayak sebelumnya

1. Buka github.com → **New repository** → nama bebas → **Private** → jangan centang README/.gitignore.
2. Dari laptop kamu (bukan di sini):
   ```bash
   cd "path/ke/File cPanel"
   git remote add origin https://github.com/USERNAME_GITHUB/absensi-mi-alamien.git
   git push -u origin main
   ```
   ```bash
   cd "path/ke/aplikasi-belajar-mi 2"
   git remote add origin https://github.com/USERNAME_GITHUB/mifda-belajar.git
   git push -u origin main
   ```
   Kalau diminta login, pakai Personal Access Token (GitHub → Settings → Developer settings → Personal access tokens → generate, scope `repo`) sebagai password.

## Bagian 2 — Pastiin Key #1 (GitHub Actions → Server) udah ke-authorize

1. cPanel → **SSH Access** → **Manage SSH Keys**.
2. Cek key yang kamu buat tadi — kalau statusnya bukan "Authorized", klik **Manage** di sebelah key itu → **Authorize**.
3. Ambil isi PRIVATE key-nya: masih di halaman yang sama, cari opsi **View/Download Key** pada key tersebut → copy semua isinya (termasuk baris `-----BEGIN ... PRIVATE KEY-----` dan `-----END ... PRIVATE KEY-----`).
4. Catat juga: **hostname/IP server** dan **port SSH**-nya — biasanya kelihatan di halaman SSH Access bagian atas (contoh: `Host: server123.hostingmu.com`, `Port: 21098` — port SSH cPanel sering BUKAN 22, harus dicek, jangan asal isi 22).

## Bagian 3 — Setup Key #2 (Server → GitHub) lewat Terminal cPanel

Buka **Terminal** di cPanel, jalanin (ganti nama file sesuai app):

```bash
ssh-keygen -t ed25519 -f ~/.ssh/deploy_absensi -N "" -C "deploy-absensi"
cat ~/.ssh/deploy_absensi.pub
```

Copy output `cat` di atas → GitHub repo Absensi → **Settings** → **Deploy keys** → **Add deploy key** → paste → **jangan centang** "Allow write access" (read-only cukup, ini cuma buat pull) → **Add key**.

Ulangi buat Mifda dengan nama file beda (`deploy_mifda`), didaftarin ke repo Mifda.

Tambahin file `~/.ssh/config` (buat lewat Terminal juga, `nano ~/.ssh/config`), isinya:

```
Host github.com-absensi
    Hostname github.com
    IdentityFile ~/.ssh/deploy_absensi
    User git

Host github.com-mifda
    Hostname github.com
    IdentityFile ~/.ssh/deploy_mifda
    User git
```

Test masing-masing (harus muncul "successfully authenticated"):
```bash
ssh -T git@github.com-absensi
ssh -T git@github.com-mifda
```

## Bagian 4 — Retrofit folder LIVE jadi git repo (HATI-HATI, one-time, backup dulu)

Ulangi buat kedua app. Ini langkah paling kritis — dilakuin sekali doang, salah dikit bisa bikin situs down, jadi:

**Backup dulu**: cPanel File Manager → klik folder live app-nya → **Compress** → jadi `.zip` → download / simpen aman. 30 detik doang, jangan diskip.

Lewat Terminal cPanel:

```bash
cd /home/GANTI_USERNAME/absensi.ynt.my.id   # folder LIVE yang sekarang, sesuaikan path
git init
git remote add origin git@github.com-absensi:USERNAME_GITHUB/absensi-mi-alamien.git
git fetch origin main
git reset --hard origin/main
```

`git reset --hard` di sini AMAN buat `.env`, `storage/` (upload real, sesi, log), `vendor/` — semua itu status "untracked/ignored" jadi gak pernah disentuh git (cuma file yang di-track di repo yang di-overwrite, dan isinya sama kayak kode yang udah kita develop bareng, jadi harusnya identik). Yang WAJIB dihindari: jangan pernah jalanin `git clean -fd` di folder ini — itu beda dari reset, itu bakal BENERAN hapus file untracked kayak `.env` dan upload asli.

Abis itu:
```bash
composer install --no-dev --optimize-autoloader --no-interaction
```
(vendor/ belum ke-generate dari git reset karena emang gak ditrack — sekali ini aja manual, abis ini otomatis lewat `deploy.sh`)

Buka domain live-nya, pastiin masih jalan normal. Kalau ada yang aneh, tinggal extract lagi backup .zip tadi ke folder ini buat balikin ke kondisi semula.

Ulangi persis buat Mifda (folder `/home/GANTI_USERNAME/mifda.my.id`, remote `github.com-mifda`).

## Bagian 5 — Isi `deploy.sh` dan Secrets GitHub

Buka `.github/workflows/deploy.sh` di masing-masing project (udah aku buatin), ganti `GANTI_USERNAME` dan path-nya sesuai folder live asli (path yang sama kayak Bagian 4).

Di tiap repo GitHub → **Settings** → **Secrets and variables** → **Actions** → tambahin:

| Secret | Isi |
|---|---|
| `SSH_PRIVATE_KEY` | Private key dari Bagian 2 langkah 3 |
| `SERVER_HOST` | Hostname/IP server dari Bagian 2 langkah 4 |
| `SERVER_PORT` | Port SSH dari Bagian 2 langkah 4 (jangan lupa isi, jangan dikosongin) |
| `CPANEL_USERNAME` | Username cPanel kamu |

## Bagian 6 — Testing

1. Bikin perubahan kecil di lokal, commit, push ke `main`.
2. Buka tab **Actions** di repo GitHub → tunggu sampai centang hijau (~30-60 detik).
3. Cek folder live di File Manager — file yang diubah harusnya udah ke-update otomatis.
4. Buka domain live, pastiin normal.

Kalau merah/gagal, buka log run-nya di tab Actions — biasanya ketauan di step mana (SSH gagal connect = salah host/port/key, git pull gagal = deploy key Bagian 3 belum bener). Kirim errornya ke saya kalau butuh bantuan.

## Alur kerja harian (setelah semua di atas beres sekali)

```bash
git add -A
git commit -m "deskripsi perubahannya"
git push
```

Selesai — otomatis ke-deploy dalam hitungan detik, gak perlu buka cPanel sama sekali.

- Perubahan tampilan (CSS/JS/Tailwind/Vite): jalanin `npm run build` DULU sebelum commit (server gak ada Node, jadi build tetep manual di lokal), biar `public/build` ikut ke-commit.
- Perubahan PHP (controller, model, job, migration): langsung push, gak perlu build apa-apa. Migration baru otomatis ke-jalanin (`migrate --force` ada di `deploy.sh`).
