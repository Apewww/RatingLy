# RatingLy - Platform Review & Rating

Tugas Besar Mata Kuliah **Industri Perangkat Lunak (IPL)** / **Rekayasa Perangkat Lunak**  
Program Studi Teknik Informatika, Fakultas Sains dan Informatika, Universitas Jenderal Achmad Yani (Unjani)

---

## 👥 Anggota Kelompok
1. **Apew** ([@Apewww](https://github.com/Apewww))
2. **pepvri** ([@pepvri](https://github.com/pepvri))
3. **Reqi Fauzan** ([@ReqiFauzan](https://github.com/ReqiFauzan))
4. **Diden Diki Gustiar** ([@DidenDikiGustiar](https://github.com/DidenDikiGustiar))
5. **Ridwan** ([@Ridwan3333](https://github.com/Ridwan3333))

---

## 🚀 Persyaratan Sistem & Tech Stack
- **Framework**: Laravel 11.x (PHP ^8.2)
- **Database**: MySQL / MariaDB / SQLite
- **Node.js & NPM**: ^18.x / ^20.x
- **Dependency Manager**: Composer

---

## 🛠️ Langkah Menjalankan Project (Local Setup)

1. **Clone Repository**
   ```bash
   git clone https://github.com/Apewww/RatingLy.git
   cd RatingLy
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript Dependencies**
   ```bash
   npm install && npm run build
   # atau untuk mode development:
   # npm run dev
   ```

4. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Sesuaikan konfigurasi database (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) pada file `.env`.*

5. **Jalankan Database Migration & Seeder**
   ```bash
   php artisan migrate
   ```

6. **Jalankan Development Server**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui browser di `http://127.0.0.1:8000`.

---

## 📌 Standar Pengembangan Kelompok
- Gunakan format branching yang rapi (`feat/...`, `fix/...`, `docs/...`).
- Buat Pull Request (PR) sebelum menggabungkan fitur ke branch `main`.
- Gunakan konvensi penamaan commit yang deskriptif (*Conventional Commits*).
