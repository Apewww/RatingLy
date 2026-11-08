# RatingLy Git & Development Workflow Guide

Panduan alur kerja (*development workflow*) standar untuk seluruh pengembang kelompok dan AI Agent (Antigravity, Cursor, Claude Code, GitHub Copilot) pada repositori **RatingLy (Laravel 11)**.

---

## 1. Skema Percabangan (Branching Model)

Proyek ini menerapkan model branching terstruktur dengan tiga tingkatan branch:

```text
main (Production Release / Demo Kampus)
  ▲
  │ (Pull Request teruji & disetujui)
development (Integrasi Bersama / Staging)
  ▲
  │ (Pull Request per fitur)
feat/modul-{nomor}-{nama-fitur} (Branch Pengembang / AI Agent)
```

### Penjelasan Cabang:
- **`main`**: Kode stabil yang siap dipresentasikan ke dosen penguji dan ter-deploy ke server production homelab. Dilarang melakukan *direct push* ke `main`.
- **`development`**: Cabang penggabungan aktif seluruh fitur kelompok. Semua pengujian integrasi dilakukan di sini sebelum rilis ke `main`.
- **`feat/...`**: Cabang kerja spesifik untuk satu fitur. Selalu dibuat dari branch `development` terbaru.
- **`fix/...`**: Cabang untuk perbaikan bug yang ditemukan saat tahap integrasi atau QA.

---

## 2. Alur Kerja Harian (Step-by-Step Cycle)

Setiap anggota kelompok atau AI Agent wajib mengikuti 6 langkah berikut:

### Langkah 1: Sinkronisasi Branch Development Terbaru
Sebelum memulai pekerjaan baru, pastikan branch lokal Anda sinkron:
```bash
git checkout development
git pull origin development
```

### Langkah 2: Buat Feature Branch Baru
Gunakan konvensi penamaan: `feat/modul-{nomor}-{nama-fitur}` atau `fix/{isu-yang-diperbaiki}`.
```bash
# Contoh untuk Modul 4 (Rating Multi-Kriteria)
git checkout -b feat/modul-4-criteria-slider

# Contoh untuk Modul 3 (Katalog Filter)
git checkout -b feat/modul-3-catalog-filter
```

### Langkah 3: Eksekusi Kode Sesuai Standar SDD & Clean Code
- Terapkan **Service Layer Pattern**: controller ramping (< 80 baris), logika bisnis di `app/Services/`.
- Validasi input form melalui **Form Requests** di `app/Http/Requests/`.
- Gunakan komponen **Anti-Slop UI** (Tailwind CSS netral + Lucide SVG Icons, tanpa emoji norak di tombol/badge).
- Pastikan hanya menyentuh file dalam domain modul masing-masing (lihat pembagian di [**AGENTS.md**](./AGENTS.md)).

### Langkah 4: Verifikasi & Self-Check Lokal
Sebelum melakukan commit, jalankan pengujian lokal:
```bash
# 1. Jalankan migrasi database
php artisan migrate

# 2. Pastikan build aset frontend bersih
npm run build

# 3. Jalankan automated test (jika ada)
php artisan test
```

### Langkah 5: Commit Menggunakan Conventional Commits
Format pesan commit:
`<tipe>(<scope>): <penjelasan ringkas menggunakan kata kerja imperatif>`

| Tipe | Penggunaan | Contoh |
| :--- | :--- | :--- |
| **`feat`** | Penambahan fitur baru | `feat(review): implement 4-criteria interactive slider form` |
| **`fix`** | Perbaikan bug | `fix(catalog): resolve price range query filtering mismatch` |
| **`refactor`** | Perapihan kode tanpa ubah fungsi | `refactor(place): extract average score math into PlaceService` |
| **`docs`** | Pembaruan dokumentasi/panduan | `docs(workflow): add pull request review checklist` |
| **`test`** | Penambahan atau perbaikan unit test | `test(auth): add feature test for verified member role` |

```bash
git add .
git commit -m "feat(review): implement 4-criteria interactive slider form"
```

### Langkah 6: Push & Buat Pull Request (PR)
Push cabang ke remote repository dan buka Pull Request ke branch **`development`**:
```bash
git push -u origin feat/modul-4-criteria-slider
```
Di antarmuka GitHub:
1. Targetkan base branch ke **`development`** (BUKAN ke `main`).
2. Isi deskripsi PR dengan ringkasan perubahan dan screenshot/bukti hasil pengujian lokal.
3. Minta review dari rekan kelompok atau lead sebelum di-merge.

---

## 3. Checklist Sebelum Merge ke `development`

Setiap Pull Request harus memenuhi kriteria berikut:
- [ ] Tidak ada konflik (*merge conflict*) dengan branch `development`.
- [ ] Logika kalkulasi bisnis berada di `app/Services/`, bukan berserakan di controller.
- [ ] Validasi form menggunakan Form Request class.
- [ ] Aset gambar/struk tidak di-commit langsung ke git jika ukurannya besar (gunakan storage link atau seeder).
- [ ] Format kode rapi sesuai PSR-12 / Laravel Pint (`vendor/bin/pint` jika tersedia).
- [ ] UI mematuhi standar desain netral dan responsif di mobile & desktop.

---

## 4. Rilis ke Production (`main`) & Server Staging

Penggabungan ke `main` dan deploy server dilakukan secara terkontrol oleh Lead / Tim Rilis:
1. Pengujian integrasi menyeluruh di branch `development`.
2. Penggabungan `development` -> `main` melalui Pull Request rilis.
3. Sinkronisasi kode di CT 113 Homelab (`git pull origin production`).
