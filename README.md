# RuangRelawan

Platform kolaborasi dan pendaftaran relawan untuk berbagai kegiatan sosial dan kemanusiaan. Proyek ini dikembangkan oleh Kelompok D (PPL).

---

## 🚀 Panduan Setup Proyek (Untuk Rekan Tim)

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek di laptop masing-masing:

### 1. Clone Repositori
```bash
git clone https://github.com/PPL-Kelompok-D/RuangRelawan.git
cd RuangRelawan
```

### 2. Buat Branch Baru untuk Pengerjaan Fitur
**Jangan bekerja langsung di branch `main`**. Buat branch sesuai format tugas/sprint:
```bash
# Contoh:
git checkout -b sprint-1-us-NamaKamu
```

### 3. Setup Lingkungan (Environment)
```bash
# 1. Install dependensi PHP
composer install

# 2. Salin template .env
copy .env.example .env     # (Windows CMD / PowerShell)
# cp .env.example .env     # (Git Bash / Mac / Linux)

# 3. Generate Application Key
php artisan key:generate
```

### 4. Setup Database MySQL (`ruangrelawan`)
Pastikan MySQL (Laragon / XAMPP) sudah aktif:
1. Buat database baru bernama **`ruangrelawan`** di MySQL / phpMyAdmin.
2. Import file skema database:
   - Lokasi file: `database/RuangRelawan.sql`
   - Atau lewat terminal / command prompt:
     ```bash
     mysql -u root -p ruangrelawan < database/RuangRelawan.sql
     ```
3. Sesuaikan username & password database di file `.env` jika berbeda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=ruangrelawan
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### 5. Jalankan Server Lokal
```bash
php artisan serve
```
Akses aplikasi melalui browser di: `http://127.0.0.1:8000`

---

## 📌 Alur Kerja Git (Git Workflow)
1. Selalu tarik perubahan terbaru dari `main` sebelum mulai:
   ```bash
   git checkout main
   git pull origin main
   ```
2. Kerjakan di branch masing-masing, lalu commit secara berkala:
   ```bash
   git add .
   git commit -m "feat(us-xx): deskripsi fitur"
   git push -u origin nama-branch-kamu
   ```
3. Buka GitHub dan buat **Pull Request (PR)** ke branch `main`.
