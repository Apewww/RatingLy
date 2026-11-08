# RatingLy AI Agent Guidelines & Architecture Rules

Dokumen ini adalah aturan wajib (**living contract**) untuk semua AI Coding Agent (Cursor, Antigravity, Claude Code, GitHub Copilot, Windsurf, dll) serta anggota pengembang repositori **RatingLy (Laravel 11)**.

---

## 1. Domain Model & Konteks Bisnis (SDD Reference)
RatingLy adalah **Platform Aggregator Ulasan Tempat & Kuliner Multi-Kriteria** berbasis Laravel 11 dengan 3 pilar:
1. **Multi-Criteria Rating**: 4 parameter terpisah (skor 1.0 – 5.0):
   - `taste_score` (Rasa Makanan/Minuman)
   - `ambience_score` (Kenyamanan Suasana)
   - `wifi_socket_score` (Fasilitas Wi-Fi & Colokan)
   - `price_value_score` (Rasio Nilai Harga)
2. **Anti-Spam & Verified Review**: Bukti struk pembayaran diupload ke `review_media` dengan siklus hidup: `unverified` -> `pending` -> `verified`. Ulasan `verified` memiliki bobot lebih tinggi dalam kalkulasi `avg_total_score`.
3. **Katalog & Filter Eksplorasi**: Filter kategori tempat, rentang harga (*price range*), filter batas minimum skor per kriteria, dan upvotes helpful.

---

## 2. Kaidah Arsitektur & Clean Code (Laravel 11)

### A. Batas Ukuran Kode & Kompleksitas (Strict Limits)
Untuk menjaga kode tetap bersih, mudah dibaca, dan mencegah *God Class* atau method raksasa:
- **Panjang Method / Fungsi**: **Maksimal 20 – 30 baris**. Jika fungsi melebihi batas ini, wajib di-refactor atau dipecah ke *private helper* atau Service terpisah.
- **Panjang Controller Class**: **Maksimal 80 – 100 baris**. Controller hanya bertindak sebagai *traffic police* (terima request -> panggil Form Request/Service -> return response).
- **Panjang Service Class**: **Maksimal 150 – 200 baris**. Jika domain membesar, pecah Service berdasarkan spesialisasi (contoh: pisahkan kalkulasi skor dari penanganan file storage struk).
- **Maksimal Parameter Fungsi**: **Maksimal 3 parameter**. Gunakan array tervalidasi atau DTO ketimbang passing 6-8 variabel terpisah.
- **Guard Clauses & Early Return**: Hindari *nested if-else* piramida bertingkat. Selalu periksa kondisi gagal/invalid di awal fungsi dan lakukan `return` / `throw` seketika agar alur logika utama tetap lurus (*flat hierarchy*).

### B. Strict Separation of Concerns (Service Layer Pattern)
- **Controller Ramping (Skinny Controllers)**: Controller hanya bertugas menangani HTTP Request, validasi via Form Request, memanggil Service/Action, dan me-return View atau JsonResponse.
- **Service Layer**: Logika bisnis kompleks (misalnya: hitung rata-rata agregat rating multi-kriteria, pembobotan ulasan berstruk, verifikasi foto struk) **WAJIB** berada di dalam `app/Services/` (contoh: `ReviewCalculationService`, `ModerationService`, `PlaceService`).
- **Form Requests**: Validasi tidak boleh ditulis langsung di dalam controller `validate()`. Gunakan Form Request khusus di `app/Http/Requests/` (contoh: `StoreReviewRequest`, `UpdatePlaceRequest`).
- **Query Scopes**: Filter database yang kompleks wajib dibungkus ke dalam Local Query Scopes di Model (contoh: `Place::filterCategory($id)->minWifiScore(4.0)`).
- **Blade Views Tanpa Query**: Dilarang mengeksekusi query database di dalam file `.blade.php`. Semua data wajib di-supply matang dari Controller.

### C. Aturan Penamaan & Konvensi
- **Model & Database**:
  - Model: Singular PascalCase (`Place`, `Review`, `MultiCriteriaScore`, `ReviewMedia`, `Upvote`, `Bookmark`, `Report`).
  - Tabel: Plural snake_case (`places`, `reviews`, `multi_criteria_scores`, dll).
  - Tipe Skor: Selalu simpan rating dalam `DECIMAL(2,1)` untuk skor kriteria dan `DECIMAL(3,2)` untuk skor agregat total. Jangan pernah gunakan `FLOAT`.
- **Intent-Revealing Names**:
  - Variabel harus ekspresif (hindari variabel 1 huruf seperti `$p`, `$r`). Gunakan `$place`, `$review`, `$criteriaScores`.
  - Method boolean wajib berawalan predikat tanya: `isVerified()`, `hasReceipt()`, `canBeEditedBy($user)`.
- **Enums & Roles**:
  - Role User: Gunakan PHP 8.2 native enum atau strict value (`guest`, `member`, `admin`).
  - Verification Status: (`unverified`, `pending`, `verified`).
- **Clean Code (YAGNI & Anti-Overengineering)**:
  - Jangan buat abstraksi interface jika implementasinya hanya satu.
  - Hindari package pihak ketiga yang berlebihan jika fitur native Laravel 11 sudah menyediakannya.

### D. Frontend & UI Guidelines (Blade + Tailwind CSS + Alpine.js)
- **Anti-Slop UI Standard**:
  - Gunakan palet warna netral (`bg-slate-50`, card `bg-white`, border `border-slate-200`, text `text-slate-900` dan `text-slate-500`).
  - Accent Primary: Indigo/Amber hangat modern (`#4F46E5` atau `#D97706`).
  - Verified Badge: Emerald netral (`bg-emerald-50 text-emerald-700 border-emerald-200`).
  - Dilarang keras memakai emoji Unicode norak (`🍔`, `🔥`, `✨ AI Insight`). Gunakan **Lucide Icons** SVG konsisten.
  - Form Slider Kriteria: Komponen interaktif berbasis Alpine.js dengan feedback nilai skor numerik langsung saat digeser (1.0 s/d 5.0).

---

## 3. Pembagian Modul & Batasan Fitur (Jobdesk 5 Orang)

AI Agent yang bekerja pada task tertentu **HANYA** boleh memodifikasi file dalam cakupan modul terkait guna mencegah *git merge conflict*:

| Modul | Domain Fitur | Controller / Service Utama | Scope View / Blade |
| :--- | :--- | :--- | :--- |
| **Modul 1** | Auth, Profile & Role Middleware | `AuthController`, `ProfileController` | `resources/views/auth/`, `profile/` |
| **Modul 2** | Manajemen Tempat (Admin CRUD) | `PlaceController`, `PlaceService` | `resources/views/admin/places/`, `places/show.blade.php` |
| **Modul 3** | Katalog & Filter Multi-Kriteria | `CatalogController`, `CatalogFilterService` | `resources/views/catalog/`, `components/place-card.blade.php` |
| **Modul 4** | Engine Ulasan & Pembobotan Struk | `ReviewController`, `ReviewCalculationService` | `resources/views/reviews/`, `components/rating-slider.blade.php` |
| **Modul 5** | Interaksi Sosial & Moderasi Admin | `SocialController`, `ModerationController` | `resources/views/admin/moderation/`, `components/upvote-button.blade.php` |

---

## 4. Standar Git & Conventional Commits

Setiap AI Agent atau pengembang wajib membuat commit dengan format **Conventional Commits**:
- `feat(scope): deskripsi fitur baru`
- `fix(scope): perbaikan bug`
- `refactor(scope): refactor kode tanpa mengubah fungsi`
- `test(scope): penambahan atau perbaikan unit/feature test`
- `docs(scope): pembaruan dokumentasi`

**Branching Strategy**:
- `main`: Branch stabil production / laporan akhir.
- `development`: Branch integrasi bersama.
- `feat/nama-fitur`: Branch fitur individual (misal: `feat/multi-criteria-slider`, `feat/receipt-verification`).
- Selalu ajukan **Pull Request (PR)** ke branch `development` sebelum masuk ke `main`.
