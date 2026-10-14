# AI Agent Operating Guidelines: petshop

Panduan kerja resmi untuk pengembangan AI-driven pada proyek **petshop**.

---

## 1. Parameter Runtime & Akses Proyek
- **Nama Proyek**: `petshop`
- **Container App**: `petshop-app-1`
- **Port Host**: `8805`
- **Live Preview URL**: `http://100.108.222.30:8805`
- **Database**: `laravel13_petshop` (User: `usr_petshop`)

---

## 2. Area Kerja & File Editing
- Seluruh kode aplikasi (Controller, Model, Blade view, Route, Migration) berada di dalam folder `src/`.
- Jangan mengedit file di luar `src/`.

---

## 3. Eksekusi Artisan (via Docker)
Host VPS tidak memiliki PHP lokal. Selalu jalankan Artisan via Docker container:
```bash
# Migrasi Database
docker exec petshop-app-1 php artisan migrate

# Generate Controller / Model
docker exec petshop-app-1 php artisan make:controller PostController
docker exec petshop-app-1 php artisan make:model Post -m

# Seeding & Cache Clear
docker exec petshop-app-1 php artisan db:seed
docker exec petshop-app-1 php artisan optimize:clear
```

---

## 4. Styling Tailwind CSS v4
Jika prompt menambahkan class Tailwind baru di file Blade:
```bash
/opt/adanix/bin/adanix build petshop
```

---

## 5. Verifikasi Live Preview Wajib
Setelah menyelesaikan fitur:
1. Periksa respons HTTP:
   ```bash
   curl -s -o /dev/null -w "%{http_code}\n" http://100.108.222.30:8805
   ```
   *(Harus menghasilkan `200`)*
2. Buka di browser: `http://100.108.222.30:8805`

---

## 6. Git Checkpoint per Fitur
Setelah fitur terverifikasi HTTP 200, lakukan commit di folder `src/`:
```bash
cd /opt/adanix/laravel/petshop/src
git add -A
git commit -m "feat(<fitur>): <penjelasan ringkas>"
```

---

## 7. Proteksi Mutlak
Dilarang keras menyentuh:
- `/opt/adanix/laravel/_golden-laravel13`
- Proyek legacy (port 8801-8804): `ceksa`, `_template-laravel13`, `demo-laravel13`, `test-l13-generated`
