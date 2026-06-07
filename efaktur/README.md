# E-Faktur Penjualan

Laravel 12 application for authenticated invoice CRUD, invoice items, QR generation, invoice preview, and PDF export.

## Requirements

- PHP 8.3+
- Composer
- Node.js and npm
- MySQL database named `capstoner`

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
php artisan serve
```

The local `.env` used during development points to:

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=capstoner
DB_USERNAME=root
DB_PASSWORD=4321
```

## Verification

```bash
php artisan test
npm run build
```

## Notes

- Invoice totals are calculated on the server from invoice items.
- The stored `qr_payload` keeps base64 JSON invoice metadata.
- The rendered QR code opens the generated invoice PDF.
