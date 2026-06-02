# TODO - E-Faktur Laravel 12 + QR + MySQL

- [x] Update `efaktur/.env` ke MySQL: localhost:3306, database `capstoner`, user `root`, password `4321`
- [x] Install library QR code via composer

- [x] Buat migration & model untuk tabel invoice/efaktur
- [x] Buat controller untuk CRUD invoice dan endpoint generate QR
- [x] Buat view Blade: list, create, detail (QR)
- [x] Update route web
- [x] Jalankan `php artisan migrate`
- [x] Smoke test: generate QR pada halaman detail
- [x] Tambahkan test fitur untuk create invoice, QR PNG, dan PDF download
