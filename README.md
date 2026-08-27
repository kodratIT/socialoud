# Socialoud

Socialoud is a Laravel-based magazine and content management project with multilingual publishing, media, advertising, analytics, and extensible Botble plugins.

## Features

The repository includes optional plugins and integrations. Each plugin must be activated and configured before use:

- Blog posts, categories, and tags (`blog`)
- Gallery management (`gallery`)
- Advertising management (`ads`)
- Google Analytics integration (`analytics`)
- Visitor comments (`fob-comment`)
- Language and translation management (`language`, `language-advanced`, `translation`)
- RSS feeds (`rss-feed`)
- Contact forms (`contact`)
- Cookie consent (`cookie-consent`)
- Database and uploads backup/restore (`backup`)
- Activity and request logging (`audit-log`, `request-log`)
- Google Reviews display (`fob-google-reviews`)
- Sliders (`simple-slider`)
- Authors, CAPTCHA, and notes (`author`, `captcha`, `note`)
- ZarinPal payment integration (`zarinpal`)

The local ZarinPal plugin requires the external `botble/payment` package and payment-provider configuration. That dependency is not included in this repository.

## Technology

- PHP 8.3 or newer on PHP 8.x
- Laravel 13
- Botble CMS packages
- Vue 3
- Bootstrap 5.3
- Laravel Mix
- Composer
- Node.js 20 or 22+

## Requirements

Install the following before setup:

- PHP with `curl`, `gd`, `json`, `pdo`, and `zip` extensions
- The PDO driver for your database; the provided `.env.example` uses MySQL and therefore needs `pdo_mysql`
- A supported database configured for Laravel (MySQL is the template default; SQLite, MariaDB, PostgreSQL, and SQL Server are supported by the database configuration)
- Composer
- Node.js 20 or 22+
- npm

## Installation

1. Create or select a database.
2. Copy the environment template:

   ```bash
   cp .env.example .env
   ```

3. Edit `.env` and set at least `APP_URL`, `APP_NAME`, `DB_CONNECTION`, database credentials, and any external-service credentials required by your enabled plugins. Keep `APP_DEBUG=false` and use a secure production environment when deploying.
4. Run the existing setup script:

   ```bash
   composer setup
   ```

The `composer setup` script installs PHP dependencies, generates the application key, runs `php artisan migrate --force`, installs JavaScript dependencies, and builds production assets. Database settings must be valid before running it.

If you need to run each step independently:

```bash
composer install
php artisan key:generate
php artisan migrate --force
npm install
npm run prod
```

For local uploaded files, create Laravel's public storage link when needed:

```bash
php artisan storage:link
```

## Local development

Start the Laravel application:

```bash
php artisan serve
```

Build frontend assets once:

```bash
npm run dev
```

Watch and rebuild frontend assets during development:

```bash
npm run watch
```

The Laravel server and the asset watcher are separate processes, so run them in separate terminals.

## Production assets

Build minified production assets with:

```bash
npm run prod
```

The equivalent underlying command is:

```bash
npm run production
```

## Testing

The project uses PHPUnit suites under `tests/Unit` and `tests/Feature`:

```bash
php artisan test
```

You can also invoke PHPUnit directly:

```bash
vendor/bin/phpunit
```

The PHPUnit configuration sets common testing services but does not select an isolated database connection. Provide a suitable test database/configuration when running feature tests.

## Useful Artisan commands

```bash
php artisan about
php artisan route:list
php artisan config:clear
php artisan storage:link
```

## Repository structure

```text
app/             Application code
config/          Laravel and CMS configuration
database/        Migrations, seeders, factories, and SQLite database
lang/            Application translations
platform/core/   Core CMS packages
platform/plugins/Optional CMS plugins and integrations
platform/themes/ Frontend themes
resources/       Application views and frontend resources
routes/          Application routes
storage/         Runtime files and logs
tests/           Unit and feature tests
```

## Security and configuration

- Never commit `.env` or service credentials.
- Generate a fresh `APP_KEY` for each installation.
- Use `APP_DEBUG=false` outside local development.
- Set `APP_ENV=production` only with production-safe configuration and credentials.
- Configure database, mail, storage, analytics, payment, and other external services before enabling their plugins.
- Review plugin dependencies and third-party licenses before redistribution or deployment.

## License

The root `composer.json` declares the project metadata license as MIT. Bundled plugins and third-party dependencies may have their own licenses; review them before redistribution.
