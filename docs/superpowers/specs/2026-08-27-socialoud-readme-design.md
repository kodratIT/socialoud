# Socialoud README Design

## Goal

Add a public-facing `README.md` that explains what Socialoud is and gives developers enough information to install, configure, build, run, and test the project locally.

## Audience

The README serves both repository visitors and developers maintaining a local instance.

## Content

- Project title and concise description.
- Bundled optional plugins, clearly described as requiring activation/configuration: `blog` (posts/categories/tags), `gallery`, `ads`, `analytics` (Google Analytics), `fob-comment` (visitor comments), `language`, `language-advanced`, `translation`, `rss-feed`, `contact`, `cookie-consent`, `backup` (database/uploads restore), `audit-log`, `request-log`, `fob-google-reviews`, `simple-slider`, `author`, `captcha`, and `note`. Also document `zarinpal` as an optional payment integration that requires the external `botble/payment` dependency and its own provider configuration; it is not bundled in this tree.
- Verified technology requirements: the project declares a PHP 8.x runtime beginning at 8.3, Laravel 13, Vue 3, Bootstrap 5, Laravel Mix, Composer, and npm.
- Prerequisites: PHP extensions declared by `composer.json` (`curl`, `gd`, `json`, `pdo`, and `zip`), plus the PDO driver for the selected database (`pdo_mysql` for the `.env.example` MySQL default); current locked frontend dependencies require Node.js 20 or >=22.
- Installation instructions that create/select a database and copy/configure `.env.example` before running migrations; explain that `composer setup` runs `migrate --force`, `npm install`, and the production asset build.
- Manual setup commands for developers who need to control each step.
- Explicit local run commands: `php artisan serve` and `npm run dev` (or `npm run development`) in separate terminals.
- Development, watch, and production asset commands from `package.json`.
- Explicit test commands: `php artisan test` and `vendor/bin/phpunit`; note that tests use the Laravel/PHPUnit configuration and may require a test database.
- Useful verified Artisan commands such as `php artisan about`, `php artisan route:list`, `php artisan storage:link`, and `php artisan config:clear`.
- High-level repository structure.
- Security/configuration notes: keep `.env` secrets out of git, use a fresh `APP_KEY` generated for the installation, set `APP_ENV`/`APP_DEBUG` safely, and configure the database and external services before deployment.
- License section that reports the `MIT` metadata in `composer.json` without claiming a root `LICENSE` file exists.

## Approach

Use one self-contained Markdown file with a short overview first and operational setup details afterward. Avoid badges, screenshots, unsupported feature claims, new dependencies, and generated documentation.

## Verification

After writing the README, verify every documented command against `composer.json`, `package.json`, and the Laravel project configuration; inspect the resulting diff and run only the smallest applicable command checks. Git commit/push is the implementation delivery step, not a README content requirement.
