# PHP-FPM/Nginx production runtime

## Goal

Run LaraMag on a 1 GB RAM / 1 CPU VPS behind Cloudflare Tunnel without `artisan serve`, while keeping static CSS/JS delivery reliable and avoiding development dependencies at runtime.

## Architecture

Cloudflare Tunnel runs on the VPS host and forwards to `http://127.0.0.1:8082`. Compose Nginx binds only `127.0.0.1:8082:80`, serves `/var/www/html/public`, and forwards PHP requests over the private Compose network to `laravel.test:9000`. PHP-FPM 8.4 has no host port. MySQL 8.4 has no public binding by default; optional administration uses an explicit loopback mapping `127.0.0.1:3308:3306`.

## Container contract

- `laravel.test` uses a custom production PHP-FPM 8.4 image, not the Sail CLI image.
- The PHP image installs Composer production dependencies, `pdo_mysql`, GD, cURL, ZIP, mbstring, XML, intl, bcmath, SOAP, OPcache, and the extensions required by the locked application dependencies.
- Nginx uses one worker, maps `127.0.0.1:8082:80`, and passes `$http_x_forwarded_proto` (falling back to `$scheme`) to PHP-FPM. `bootstrap/app.php` trusts the private proxy network and the forwarded host/port/proto headers.
- Production containers do not mount the full source tree. The PHP image contains application code and Composer dependencies; the Nginx target copies the exact built `/var/www/html/public` tree from the PHP build stage.
- Host-backed mounts are exact paths: `./storage:/var/www/html/storage`, `./bootstrap/cache:/var/www/html/bootstrap/cache`, and `./public/storage:/var/www/html/public/storage` (also read-only in Nginx). Because this repository's `public/storage` is a real ignored upload directory, deployment preserves it and skips `storage:link` when it is a directory.
- The image build excludes `.env`, `.git`, `vendor`, `node_modules`, dumps, logs, and graphify output through `.dockerignore`.

## Asset and release flow

Before the image build, `package.json` is authoritative and `npm install --package-lock-only --ignore-scripts --no-audit --no-fund` reconciles the lockfile. The build then requires a clean `npm ci`, runs `npm run production` (Laravel Mix), runs `php artisan cms:publish:assets`, and asserts the expected `public/vendor` and `public/themes` outputs. Node/npm are build-only and are absent from the PHP runtime image.

Deployment sets the final environment first, builds the image, starts MySQL, waits for its `service_healthy` contract, runs Laravel cache preparation and `php artisan migrate --force`, then starts Nginx and PHP-FPM. No migration runs before the readiness gate.

## Resource limits

- PHP-FPM: `memory: 448M`, `cpus: 0.55`, `pm=ondemand`, `pm.max_children=2`, PHP memory limit 192 MB, OPcache 48 MB.
- MySQL: `memory: 256M`, `cpus: 0.20`, InnoDB buffer pool 96 MB, maximum 20 connections, performance schema disabled.
- Nginx: `memory: 64M`, `cpus: 0.15`, one worker. Container caps total 0.90 CPU, leaving host CPU for Docker and Cloudflare Tunnel.
- Build peak: Node is capped at 384 MB and Composer at 384 MB; asset/image builds should run during a low-traffic window or on CI when the 1 GB host cannot provide sufficient build headroom.

## Runtime settings

Production uses `APP_DEBUG=false`, the public Cloudflare domain in `APP_URL`, `FORCE_SCHEMA=https` where supported, and `SESSION_SECURE_COOKIE=true`. Nginx maps the incoming Cloudflare `X-Forwarded-Proto` header and `bootstrap/app.php` trusts `HEADER_X_FORWARDED_FOR | HEADER_X_FORWARDED_HOST | HEADER_X_FORWARDED_PORT | HEADER_X_FORWARDED_PROTO`, so Laravel preserves HTTPS URL/cookie behavior.

## Verification

- Compose config parses; Nginx and MySQL bind only loopback ports `8082` and `3308`.
- PHP-FPM reports PHP 8.4 and `php-fpm -t` passes.
- The Nginx image contains compiled CSS/JS plus CMS-published vendor/theme assets copied from the PHP build target.
- MySQL reports ready before migrations run.
- Nginx starts after PHP-FPM readiness and `curl http://127.0.0.1:8082/up` returns HTTP 200.
- A request through the Cloudflare Tunnel preserves HTTPS URL generation and secure-cookie behavior.
