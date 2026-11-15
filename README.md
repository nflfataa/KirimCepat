# 📦 KirimCepat — Layanan Pelacakan Paket & Ekspedisi Jawa Timur (K13)

Aplikasi layanan ekspedisi lokal antarkota di wilayah Jawa Timur dengan kalkulasi ongkir volumetrik, generator nomor resi otomatis, pelacakan resi publik, dan implementasi CI/CD Jenkins Multibranch Pipeline.

## 👥 Anggota Kelompok & Peran
| Nama | NIM | Peran |
| :--- | :--- | :--- |
| Muhammad Naufal Thariqul Fata | 102062400023 | Tech Lead / Backend |
| Vincent Imanuel Putra | 102062400026 | DevOps Engineer |
| Shandy Okta ramadhani | 102062400136 | Container Engineer |
| Jiyu Danjiki Ake Heriyanto | 102062400152 | QA Engineer & Tech Writer |

## 🛠️ Tech Stack
- **Backend & Web Framework:** Laravel 11 (PHP 8.3) & Blade Template
- **Database:** MySQL 8.0
- **Testing:** PHPUnit / Pest
- **Containerization:** Docker (Multi-stage) & Docker Compose
- **CI/CD:** Jenkins Multibranch Pipeline + GitHub Webhook + Ngrok

## ✨ Daftar Fitur Utama
1. **Pembuatan Pengiriman & Nomor Resi Otomatis:** Format unik `KC` + `YYYYMMDD` + `6 digit urut`.
2. **Perhitungan Ongkir Cerdas:** Membandingkan berat aktual dan berat volumetrik ($P \times L \times T / 6000$), dibulatkan ke atas per kg, dikalikan tarif zona Jawa Timur.
3. **Pembaruan Status Kurir:** Validasi *forward-only* (`DIBUAT` → `DIJEMPUT` → `TRANSIT` → `DIANTAR` → `DITERIMA`).
4. **Halaman Lacak Resi Publik:** Pencarian detail pengiriman dan riwayat perjalanan paket tanpa login.
5. **Healthcheck Endpoint:** `GET /health` untuk verifikasi status aplikasi dan koneksi database.