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
- Seed default admin user: `composer seed` or `docker-compose run --rm app composer seed`.

Docker & mPDF:
- The Docker image installs required PHP extensions (GD, intl, etc.) and will install `mpdf/mpdf` at build time using `composer update` inside the container.

Automated setup script:
- Run `./scripts/setup.sh` to perform a full setup (creates `.env` from `.env.example` if missing, builds images, starts DB, installs deps, runs migrations and seeds, builds CSS).
- Use `./scripts/setup.sh --no-docker` to run a local-only setup (composer & npm locally) or `./scripts/setup.sh --dev` to start Tailwind in dev/watch mode.
