# TeamSakit Official Web Pentest Lab

A deliberately isolated web-security training project built with PHP 8.x and MySQL. The frontend is a normal-looking community portal; training routes are separate from the normal site. It supports two separate runtime paths: Docker for development/hosting and KSWEB for local practice on Android.

## Safety defaults

- `LAB_ENABLED=false` by default. Lab routes return 404 until explicitly enabled.
- Keep this repository private while developing.
- Never deploy the vulnerable profile to a public, unrestricted service.
- Use dummy data only. Do not point scanners at third-party systems.
- The vulnerable profile is for an isolated local environment; the fixed profile is the baseline for normal hosting.
- SSRF and file-access exercises must use local simulation fixtures only; no arbitrary external fetches or host filesystem access.

## Option A — Docker Compose

Copy `.env.example` to `.env`, replace the example passwords, and run:

```sh
docker compose up --build
```

Open `http://127.0.0.1:8080`. To enable the isolated practice routes locally, set `LAB_ENABLED=true` in `.env` and restart. Keep the port bound to loopback and do not expose vulnerable labs to the internet.

On Android/Termux, Docker itself is not natively supported on every setup. If Docker is unavailable, use a Linux machine/VM or use the KSWEB instructions below for local PHP/MySQL practice.

## Option B — KSWEB on Android

See [docs/KSWEB.md](docs/KSWEB.md) for step-by-step setup.

Summary:
1. Set KSWEB's web Document Root to the repo's `public/` folder.
2. Start KSWEB's PHP web server and MySQL service.
3. Create the `teamsakit_lab` database and import `database/init.sql`.
4. Copy `app/config.local.example.php` to `app/config.local.php` and edit the database settings.
5. Open the local address and port shown by KSWEB.

The KSWEB setup needs PHP PDO MySQL and URL rewriting for friendly paths. Use Apache if available, since `public/.htaccess` contains the rewrite rules. KSWEB does not run Docker Compose.

The local config file `app/config.local.php` is ignored by Git. Do not commit real passwords or publish this file. The example template is safe to keep in the repository.

## Environment variables and local config

Docker/hosting reads environment variables from its runtime. KSWEB can use the ignored `app/config.local.php` because Android app settings may not provide environment variables in the same way. Environment variables take priority over local config values. Never commit `.env` or `app/config.local.php`.

## Project layout

- `public/` — web document root, PHP entrypoint, assets, and Apache rewrite rules
- `app/` — routing, config loader, and PDO database connection; local KSWEB config template
- `database/` — schema and safe dummy seed data
- `docs/` — setup instructions, scenario roadmap, and migration notes
- `profiles/fixed/` and `profiles/vulnerable/` — mode guidance
- `docker-compose.yml`, `Dockerfile`, `docker/` — Docker runtime

## Learning roadmap

The 16 planned scenarios are documented in `docs/SCENARIOS.md`. Four starter handlers, L01–L04, are currently present. The scaffold keeps lab access off by default; implement and verify each scenario locally before enabling it.

## MySQL to PostgreSQL/Supabase

Supabase uses PostgreSQL, not MySQL. To migrate, change PDO from `pdo_mysql` to `pdo_pgsql`, update the DSN, review schema types/identity columns, replace MySQL-only SQL (such as `INSERT IGNORE`), revise collation/boolean syntax, configure PostgreSQL credentials through environment variables, and re-test every scenario. It is not a drop-in connection-string change.

## Responsible use

Only test systems you own or have explicit permission to assess. Training vulnerabilities are intentionally separated from the secure application and must remain access-controlled.
