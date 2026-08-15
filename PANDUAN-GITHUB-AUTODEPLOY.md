# Panduan Auto-Deploy: Local → GitHub → cPanel

Alur akhirnya: kamu `git push`, GitHub Actions otomatis manggil cPanel buat `pull` commit terbaru + jalanin composer/migrate/cache. Gak perlu upload manual/FTP lagi.

Berlaku buat 2 project: **Absensi** (`File cPanel/`) dan **Mifda** (`aplikasi-belajar-mi 2/`) — masing-masing repo GitHub terpisah, deploy terpisah.

Karena hosting kamu gak ada SSH, semua langkah di bawah pakai fitur bawaan cPanel (**Git™ Version Control** + **API Token**), gak butuh Terminal/SSH sama sekali.

## Yang udah aku siapin di kedua project

- `git init` + commit pertama — kode kamu sekarang udah jadi repo git beneran (lokal), tinggal dorong ke GitHub.
- `.cpanel.yml` — resep deploy yang bakal dijalanin cPanel tiap kali ada pull baru: copy file → `composer install` → `migrate --force` → cache clear.
- `.github/workflows/deploy.yml` — workflow GitHub Actions yang manggil cPanel buat deploy tiap ada push ke branch `main`.
- `.gitignore` disesuaikan: `/public/build` (hasil build Vite) **sengaja gak di-ignore** karena server gak ada Node/npm buat build otomatis — jadi kamu build lokal dulu sebelum push (dijelasin di bagian alur harian).
- Dicek: `.env` beneran gak ke-track (aman, kredensial gak bakal ke-push ke GitHub).

## Bagian 1 — Buat repo di GitHub (2x, satu per app)

1. Buka github.com → **New repository**.
2. Kasih nama (bebas, misal `absensi-mi-alamien` dan `mifda-belajar`), pilih **Private** (rekomendasi — walau `.env` aman, kode sistem sekolah beneran lebih baik gak publik), **jangan** centang "Add README"/".gitignore" (biar gak bentrok sama commit yang udah ada).
3. Push repo lokal ke situ — jalanin di terminal laptop kamu (bukan di sini), dari folder masing-masing project:

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

   Kalau diminta login, GitHub sekarang wajib pakai **Personal Access Token** (bukan password biasa) — buat di GitHub → Settings → Developer settings → Personal access tokens → Generate new token (classic, scope `repo`), pakai token itu sebagai password pas `git push` minta login.

## Bagian 2 — Sambungkan cPanel ke repo GitHub (Git Version Control)

Ulangi untuk kedua app. Di cPanel:

1. Cari fitur **Git™ Version Control** (search box cPanel) → **Create**.
2. **Clone URL**: karena repo private dan gak ada SSH, pakai format HTTPS dengan token nempel:
   ```
   https://USERNAME_GITHUB:GANTI_PERSONAL_ACCESS_TOKEN@github.com/USERNAME_GITHUB/absensi-mi-alamien.git
   ```
   (Buat token baru khusus ini kalau mau, scope `repo` read cukup — beda dari token yang dipakai `git push` di langkah 1, biar gampang di-revoke terpisah kalau perlu.)
3. **Repository Path**: folder BARU yang KOSONG, terpisah dari folder live sekarang. Ini cuma tempat "staging" clone-nya, bukan folder yang diakses publik. Contoh: `/home/USERNAME_CPANEL/repositories/absensi`.
4. Klik **Create**. Tunggu sampai selesai clone.
5. Catat path lengkap Repository Path ini — dipakai lagi di Bagian 4.

**Kenapa gak clone langsung ke folder live yang sekarang dipakai domain?** Karena folder itu udah isi (aplikasi yang jalan + database real), dan cPanel Git Version Control cuma bisa clone ke folder kosong. Makanya polanya: clone ke folder staging terpisah, terus `.cpanel.yml` yang tugasnya nyalin file dari staging ke folder live tiap kali ada deploy (sudah aku siapin, lihat isi `.cpanel.yml` di masing-masing repo — cuma perlu kamu isi 2 placeholder di dalamnya, lihat Bagian 3).

## Bagian 3 — Isi placeholder di `.cpanel.yml`

Buka `.cpanel.yml` di masing-masing project (udah aku buatin), ganti:

- `GANTI_USERNAME` → username cPanel kamu.
- Path `DEPLOYPATH` → path folder LIVE yang sekarang beneran dipakai domain (cek di cPanel **File Manager**, biasanya `/home/USERNAME_CPANEL/absensi.ynt.my.id/` atau `/home/USERNAME_CPANEL/public_html/` tergantung setup addon domain kamu).

Kalau nanti pas deploy pertama error "php: command not found" atau "composer: command not found", buka cPanel → **Select PHP Version**, cek versi yang dipakai (harus 8.1+), lalu ganti baris `php artisan ...` di `.cpanel.yml` jadi path lengkap, polanya:
```
/opt/cpanel/ea-php81/root/usr/bin/php artisan migrate --force
```
(ganti `ea-php81` sesuai versi PHP yang aktif). Commit + push perubahan ini abis ditest jalan.

## ⚠️ REVISI PENTING — hostingmu gak support full-otomatis

Udah dicek pakai script diagnostik: hostingmu mematikan `shell_exec`, `exec`, `proc_open`, dan gak ada fitur **API Tokens** maupun **SSH Access**. Artinya 3 cara "otomatis tanpa klik apa-apa" (GitHub Actions + API Token, webhook PHP, push-deployment SSH) semuanya gak bisa dipakai di paket hosting ini — bukan salah setup, memang dikunci dari sononya.

**Yang masih 100% bisa dan tetap jauh lebih enak dari upload manual/FTP:** cPanel Git Version Control (Bagian 1-3 di atas tetap dipakai persis kayak gitu) + **2 klik manual** di cPanel setiap abis `git push`. Gak butuh SSH, API Token, atau `.github/workflows` sama sekali — makanya file workflow-nya udah aku hapus (gak akan pernah bisa jalan tanpa API Token).

### Cara deploy (2 klik, ~15 detik)

Setiap abis `git push` ke GitHub:

1. Buka cPanel → **Git™ Version Control** → klik **Manage** di repo yang mau diupdate.
2. Tab **Pull or Deploy** → klik **Update from Remote** (ini yang narik commit terbaru dari GitHub).
3. Klik **Deploy HEAD Commit** (ini yang jalanin `.cpanel.yml` — copy file ke folder live, `composer install`, `migrate --force`, cache clear — semua otomatis begitu diklik).
4. Selesai. Buka domain live buat mastiin gak error.

### Testing pertama

1. Pastiin `.env` (versi PRODUCTION) udah ada manual di folder live cPanel — gak ikut ke-push lewat git (sengaja, demi keamanan), jadi kalau folder live belum punya `.env`, upload sekali manual lewat File Manager sebelum testing.
2. Bikin perubahan kecil yang aman di lokal, commit, push ke `main`.
3. Lakuin 2 klik di atas.
4. Cek folder live di File Manager — file yang diubah harusnya udah ke-update. Buka domain live, pastiin normal.

### Alur kerja harian

```bash
git add -A
git commit -m "deskripsi perubahannya"
git push
```
— lanjut 2 klik di cPanel di atas. Kalau ada perubahan tampilan (CSS/JS/Tailwind), jalanin `npm run build` DULU sebelum commit, biar `public/build` ikut ke-commit.

### Kalau mau BENERAN otomatis (gak perlu klik sama sekali)

Satu-satunya jalan: minta hosting kamu aktifin salah satu dari 2 fitur ini (keduanya fitur cPanel standar, biasanya gratis tinggal minta via tiket support, bukan upgrade paket):
- **SSH Access** (walau cuma buat 1x setup awal), ATAU
- **API Tokens** (Security → Manage API Tokens)

Draft pesan buat tiket support:

> Halo, saya mau nanya, apakah untuk akun cPanel saya (username: yntt4626) bisa diaktifkan fitur "API Tokens" (Security → Manage API Tokens) atau "SSH Access"? Saya butuh salah satunya untuk setup auto-deploy dari GitHub. Terima kasih.

Kalau salah satu dikasih, kabari aku — aku update lagi panduannya jadi bener-bener 1x push langsung ke-deploy tanpa klik apapun.
