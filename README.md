# TeamSakit Official Web Pentest Lab

A deliberately isolated web-security training project built with PHP 8.4, MySQL 8, and Docker. The frontend is a normal-looking community portal; training routes are separate from the normal site.

## Safety defaults

- `LAB_ENABLED=false` by default. Lab routes return 404 until explicitly enabled.
- Keep this repository private while developing.
- Never deploy the vulnerable profile to a public, unrestricted service.
- Use dummy data only. Do not point scanners at third-party systems.
- The vulnerable profile is for a local, isolated Docker network; the fixed profile is the baseline for normal hosting.
- SSRF and file-access exercises must use the local simulation fixtures only; no arbitrary external fetches or host filesystem access.

## Quick start (Termux)

Install Docker support in your environment, then from the repository directory:

```sh
cp .env.example .env
# Edit .env and set non-default passwords before starting.
docker compose up --build
```

Open `http://127.0.0.1:8080`. To enable the isolated practice routes locally, set `LAB_ENABLED=true` in `.env` and restart. Do not expose that profile to the internet.

> On Android/Termux, Docker itself is not natively supported on every setup. If Docker is unavailable, use a Linux machine/VM or a supported remote development environment. Do not put secrets in GitHub.

## Environment variables

See `.env.example`. Database credentials, app mode, lab toggle, and lab gate credentials are configured outside the source code. Never commit a real `.env`.

## Project layout

- `public/` — web entrypoint and static assets
- `app/` — routing, environment config, and PDO database connection
- `database/` — schema and safe dummy seed data
- `docs/` — scenario roadmap and migration notes
- `docker-compose.yml`, `Dockerfile` — local runtime

## Learning roadmap

The 16 planned scenarios are documented in `docs/SCENARIOS.md`. The first scaffold keeps lab access off by default; implement and verify each scenario locally before enabling it.

## MySQL to PostgreSQL/Supabase

Supabase uses PostgreSQL, not MySQL. To migrate, change PDO from `pdo_mysql` to `pdo_pgsql`, update the DSN, review schema types/identity columns, replace MySQL-only SQL (such as `INSERT IGNORE`), revise collation/boolean syntax, configure PostgreSQL credentials through environment variables, and re-test every scenario. It is not a drop-in connection-string change.

## Responsible use

Only test systems you own or have explicit permission to assess. Training vulnerabilities are intentionally separated from the secure application and must remain access-controlled.
