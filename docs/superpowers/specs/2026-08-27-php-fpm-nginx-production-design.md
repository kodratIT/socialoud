# PHP-FPM/Nginx production runtime

## Goal

Run LaraMag on a 1 GB RAM / 1 CPU VPS behind Cloudflare Tunnel without using `artisan serve` as the production web server, while keeping static CSS/JS delivery reliable.

## Architecture

Cloudflare Tunnel forwards to the Compose Nginx service on host port 8082. Nginx serves static files and forwards PHP requests over the private Compose network to PHP-FPM 8.4 on port 9000. MySQL 8.4 remains private to the Compose network and is mapped to host port 3308 only for external administration.

## Container contract

- `laravel.test` becomes a PHP-FPM 8.4 application container and exposes no host port.
- `nginx` is the only public application container and maps `8082:80`.
- Application code and built assets are copied into the production image; runtime does not need Node/npm.
- Composer installs production dependencies with optimized autoloading.
- Frontend assets are compiled by Laravel Mix during the image build.
- `storage` and bootstrap cache remain writable/persistent through host mounts or named volumes.

## Resource limits

PHP-FPM uses ondemand workers with a small maximum child count. Nginx runs one worker. MySQL uses a bounded buffer pool and connection limit suitable for 1 GB RAM. No queue worker, scheduler loop, or development watcher is started by Compose.

## Runtime settings

Production defaults disable debug output and enable Laravel config/route/view caching during deployment. `APP_URL` remains deployment-specific and must be set to the public domain used by Cloudflare Tunnel.

## Verification

- Compose config parses.
- Built image reports PHP 8.4 and contains compiled frontend assets.
- PHP-FPM and Nginx containers become healthy.
- `curl` through the local Nginx port returns the Laravel application.
- MySQL remains reachable from PHP-FPM as `mysql:3306`.
