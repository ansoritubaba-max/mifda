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

## Bagian 4 — Bikin cPanel API Token

1. cPanel → **Security** → **Manage API Tokens** → **Create**.
2. Kasih nama (misal `github-actions-deploy`), scope biarin default (full access akun kamu — cukup aman karena token ini cuma dipegang GitHub Actions, disimpan terenkripsi sebagai secret).
3. **Copy token-nya SEKARANG** — cuma ditampilin sekali, kalau ke-skip harus generate baru.

Token ini SAMA dipakai buat Absensi maupun Mifda (satu akun cPanel, satu token cukup) — kecuali dua app kamu ada di 2 akun cPanel berbeda, baru butuh 2 token.

## Bagian 5 — Setup Secrets di GitHub (ulangi per repo)

Di tiap repo GitHub → **Settings** → **Secrets and variables** → **Actions** → **New repository secret**, tambahin 4 secret ini:

| Secret | Isi | Contoh |
|---|---|---|
| `CPANEL_HOSTNAME` | Hostname server cPanel (BUKAN domain absensi/mifda kamu — ini hostname servernya) | `https://server123.namahostingmu.com` |
| `CPANEL_USERNAME` | Username cPanel | `usernamekamu` |
| `CPANEL_TOKEN` | Token dari Bagian 4 | *(paste token)* |
| `CPANEL_REPO_PATH` | Repository Path dari Bagian 2 langkah 3 | `/home/usernamekamu/repositories/absensi` |

Cara cari `CPANEL_HOSTNAME`: cPanel → sidebar kiri ada info **"Server Information"**/**General Information**, atau cek email welcome dari hosting provider kamu pas pertama daftar (biasanya ada di situ). Port API-nya (2083) udah dihandle otomatis, gak perlu ditulis manual.

## Bagian 6 — Testing pertama

1. Pastiin `.env` (versi PRODUCTION, bukan yang di git) udah ada manual di folder live cPanel — ini **gak** ke-push lewat git (memang sengaja, biar kredensial aman), jadi kalau folder live belum punya `.env`, upload manual sekali lewat File Manager sebelum testing.
2. Bikin perubahan kecil yang aman di lokal (misal ubah komentar di satu file), commit, push ke `main`.
3. Buka tab **Actions** di repo GitHub → harusnya muncul workflow run baru, tunggu sampai centang hijau.
4. Cek folder live di cPanel File Manager — file yang tadi diubah harusnya udah ke-update.
5. Buka domain live-nya, pastiin masih jalan normal (gak 500).

Kalau gagal di step 3 (merah/error), buka log run-nya di GitHub Actions, biasanya ketauan errornya di mana (salah token, salah path, dll) — kirim ke saya error-nya kalau butuh bantuan diagnosis.

## Alur kerja harian (setelah semua di atas beres sekali)

Setiap kali ada perubahan kode:

```bash
git add -A
git commit -m "deskripsi perubahannya"
git push
```

- Kalau ada perubahan tampilan (CSS/JS/Tailwind/Blade yang pakai Vite), jalanin `npm run build` DULU sebelum `git add`, biar `public/build` ikut ke-commit versi terbaru. Kalau cuma ubah logic PHP (controller, model, job, dll) — gak perlu `npm run build`, langsung commit aja.
- Abis push, GitHub Actions otomatis jalan (~1-2 menit) → cPanel otomatis pull + composer install + migrate + cache clear. Gak perlu buka cPanel sama sekali.
- Migration baru? Aman — `.cpanel.yml` udah include `migrate --force` otomatis tiap deploy (additive-only, sama seperti kebiasaan migration kita selama ini).

## Kalau nanti hostingnya ternyata support SSH

Kabari aku — ada cara yang lebih ringan/cepat pakai SSH langsung (gak perlu clone-staging-terus-copy kayak sekarang), plus bisa auto-build Vite di GitHub Actions (gak perlu `npm run build` manual tiap push). Setup sekarang tetap jalan normal walau nanti pindah cara, cuma lebih simpel aja kalau SSH ternyata ada.
