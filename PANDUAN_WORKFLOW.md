# 📘 Panduan Workflow & Aturan Git Kelompok RatingLy

Selamat datang di repository resmi **RatingLy** (Tugas Besar Mata Kuliah Industri Perangkat Lunak / IPL)!  
Dokumen ini adalah acuan bersama agar proses pengerjaan fitur berjalan lancar, rapi, dan tidak ada kodingan yang saling tertimpa (*merge conflict*).

---

## 🌳 1. Struktur Branch Utama

| Nama Branch | Status | Aturan Penggunaan |
| :--- | :--- | :--- |
| **`production`** | 🔴 **Suci & Live** | Terhubung langsung dengan auto-deploy ke domain publik [ratingly.raflylabs.com](https://ratingly.raflylabs.com/). **DILARANG KERAS** push / commit langsung ke sini. Hanya diisi lewat Pull Request dari `development`. |
| **`development`** | 🟡 **Pusat Integrasi** | Cabang utama tempat berkumpulnya semua fitur yang sedang dikembangkan. Semua branch fitur dibuat dari sini dan disetor kembali ke sini via Pull Request. |
| **`feat/...` / `fix/...`** | 🟢 **Branch Kerja Pribadi** | Dibuat oleh masing-masing anggota di laptop lokal untuk mengerjakan tugas/modul yang dibagikan. |

---

## 🚀 2. Alur Pengerjaan Fitur (Step-by-Step)

### Langkah 1: Persiapan Sebelum Mulai Coding
Sebelum membuat fitur baru, **selalu tarik (*pull*) kode terbaru** dari branch `development`:
```bash
# 1. Pindah ke branch development
git checkout development

# 2. Ambil update terbaru dari GitHub
git pull origin development

# 3. Buat branch baru untuk tugas kamu
git checkout -b feat/nama-fitur-kamu
```
*Contoh format penamaan branch:*
- Fitur baru: `feat/autentikasi-user`, `feat/form-review`, `feat/katalog-produk`
- Perbaikan bug: `fix/tampilan-navbar-mobile`, `fix/kalkulasi-rating`

---

### Langkah 2: Coding & Commit di Laptop
Kerjakan kodingan seperti biasa di editor (VS Code, dll). Simpan perubahan secara berkala dengan pesan commit yang deskriptif:
```bash
git add .
git commit -m "feat: membuat halaman registrasi dan validasi email"
```

> 💡 **Standar Penamaan Commit (Conventional Commits):**
> - `feat:` untuk penambahan fitur baru (misal: `feat: tambah rating bintang`)
> - `fix:` untuk perbaikan bug / error (misal: `fix: perbaiki relasi tabel user`)
> - `style:` untuk perubahan tampilan / styling CSS (misal: `style: rapikan card produk`)
> - `docs:` untuk perubahan dokumentasi / README

---

### Langkah 3: Mengirim Kode ke GitHub (Push)
Kirim branch kerja kamu ke GitHub:
```bash
git push -u origin feat/nama-fitur-kamu
```

---

### Langkah 4: Membuat Pull Request (PR) ke `development`
1. Buka repo GitHub: **[https://github.com/Apewww/RatingLy](https://github.com/Apewww/RatingLy)**
2. Klik tombol hijau **Compare & pull request**.
3. **PENTING**: Pastikan arah panahnya adalah:  
   `base: development`  ⬅️  `compare: feat/nama-fitur-kamu`
4. Beri judul dan jelaskan perubahan apa yang sudah kamu kerjakan.
5. Beritahu teman di grup WhatsApp/Discord untuk di-review & di-merge bersama.

---

## 💾 3. Aturan Manajemen Database (Migrations)

Kita menggunakan fitur bawaan Laravel agar struktur database selalu sinkron di laptop semua anggota:
1. **Dilarang ekspor-impor manual file `.sql`** antar anggota.
2. Setiap kali menambah tabel atau mengubah kolom, selalu buat file migration:
   ```bash
   php artisan make:migration create_nama_tabel_table
   ```
3. Anggota lain cukup menjalankan perintah ini setelah melakukan `git pull`:
   ```bash
   php artisan migrate
   ```

---

## 🌐 4. Kapan Kodingan Tampil di Website Live?
- Begitu kumpulan fitur di branch `development` sudah lengkap dan stabil, Ketua Kelompok / Tim akan membuat PR dari **`development` ke `production`**.
- Begitu PR dimerge ke `production`, **server homelab otomatis mendeteksi dan melakukan auto-deploy** ke:  
  👉 **[https://ratingly.raflylabs.com/](https://ratingly.raflylabs.com/)**

Semangat mengerjakannya, rekan-rekan tim! Jika ada kendala Git atau error syntax, langsung tanyakan di grup ya.
