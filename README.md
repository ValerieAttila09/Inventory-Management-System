# Storage Management Dashboard

A lightweight PHP + MySQL project scaffold for a storage/inventory dashboard.

Quick start:

1. Copy `.env.example` to `.env` and edit DB settings
2. Install PHP deps: `composer install`
3. Install Node deps: `npm install`
4. Build CSS: `npm run build`
5. Run (dev): `php -S localhost:8000 -t public` or use Docker (`docker-compose up -d`)

This project uses PSR-4 autoloading and Twig for templating.

Migrations & database:
- Use Phinx for migrations. Configure database in `.env`.
- Run migrations locally: `composer migrate` (runs `vendor/bin/phinx migrate -e development`).

Docker & mPDF:
- The Docker image installs required PHP extensions (GD, intl, etc.) and will install `mpdf/mpdf` at build time using `composer install` inside the container.
