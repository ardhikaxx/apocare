# 📋 Changelog

Semua perubahan penting dan riwayat pengembangan proyek **Website Apocare - Integrated Pharmacy Management System** didokumentasikan dalam file ini.

Format pencatatan mengacu pada [Keep a Changelog](https://keepachangelog.com/id/1.0.0/) dan mematuhi [Semantic Versioning (SemVer)](https://semver.org/).

---

## [1.0.0] - 2026-10-12
### 🚀 Rilis Perdana (Initial Production Release)
Dikembangkan oleh **Yanuar Ardhika Rahmadhani Ubaidillah (@ardhikaxx)**.

### ✨ Added (Fitur Baru)
- 1. 🔑 Autentikasi & RBAC
- 2. 💊 Master Obat
- 3. 📦 Stok Masuk & Opname
- 4. 🧾 E-Resep & Kasir/POS
- 5. 🏭 Supplier
- 6. 📊 Laporan & Audit Log
- Dokumentasi standar: README dengan QRIS donasi, LICENSE proprietary, SECURITY.md, CONTRIBUTING.md, SUPPORT.md, CHANGELOG.md, CITATION.md.

### 🔐 Security
- Proteksi CSRF/XSS/SQL Injection sesuai standar framework.
- Password hashing (bcrypt/argon2); otorisasi backend per endpoint.
- Audit aktivitas penting bila tersedia.

---

[1.0.0]: https://github.com/ardhikaxx/apocare/releases/tag/v1.0.0
