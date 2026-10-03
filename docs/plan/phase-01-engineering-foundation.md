# Phase 1 — Engineering foundation & environments

Create the Laravel platform, development standards and deployable environments.

| | |
|---|---|
| Workstream | A. Product foundation |
| Duration | ~1 week |
| Depends on | Phase 0 scope frozen enough to name environments and domains |
| Exit gate | A minimal Laravel application deploys reliably to staging with SSR, database, Redis and admin shell |
| Backlog | P1-01, P1-02, P1-03, P1-04 |
| Decide first | D-01 hosting and domain (needed for staging, not for local work). Technical defaults T-01 to T-05 and T-12 in [../tracking/decisions.md](../tracking/decisions.md). |

## Objective

Establish a production-shaped Laravel 13 modular monolith before feature work starts, so nothing later is built on local-only assumptions.

## Before you start

The repo today is the plain Laravel skeleton. Fix these three things first.

- [ ] **Make Composer and the CLI use the same PHP.** `php -v` reports 8.5.7, but `composer diagnose` reports PHP 8.3.31 from Herd. Composer resolves packages for the PHP it runs on, so they must match.
  ```bash
  php -v
  composer diagnose      # look at "PHP version" and "PHP binary path"
  ```
  Set Herd's global PHP to 8.5 (Herd → PHP, or `herd use 8.5`) and check again. Then change `"php": "^8.3"` to `"php": "^8.5"` in `composer.json`, and run staging and production on the same version.
- [ ] **Create the MySQL databases.** MySQL 8.0.39 is installed.
  ```sql
  CREATE DATABASE brightlearners CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  CREATE DATABASE brightlearners_testing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  ```
- [ ] **Start a local Redis.** PHP has the `redis` extension but no Redis server is installed. Docker is available:
  ```bash
  docker run -d --name brightlearners-redis -p 6379:6379 --restart unless-stopped redis:7-alpine
  ```
  If Redis is not available on a given day, the `database` cache and queue drivers still work locally. Staging and production must use Redis.

## Packages and commands

| Package | Version | Why |
|---|---|---|
| React starter kit | `main` | Inertia 3, React 19, TypeScript, Fortify auth with 2FA, Wayfinder, SSR setup |
| `filament/filament` | ^5.9 | Admin panel |
| `spatie/laravel-permission` | ^8.3 | Needed now for the one `super-admin` role that guards `/admin` |

Scout arrives in Phase 5. Meilisearch is deferred until search quality demands it.

## Build steps

- [ ] **1.1** Create the repository and the application from the official React / TypeScript starter kit.

  The current folder has no custom code, so re-base instead of retrofitting.

  1. Close the editor so Windows does not lock the folder.
  2. In `D:\test`:
     ```bash
     laravel new brightlearners-kit --react --pest --database=mysql --npm --git --boost
     ```
     At the prompts choose Laravel's built-in authentication and no teams.
  3. Copy `docs/` and `.ai/` from the old folder into `brightlearners-kit`. **The plan lives in `docs/`; do not lose it.**
  4. Rename `brightlearners` → `brightlearners-skeleton-old`, then `brightlearners-kit` → `brightlearners`. Keeping the same path keeps the editor and Claude Code project settings.
  5. If Boost was not installed by step 2: `composer require laravel/boost --dev`, then `php artisan boost:install` and pick the same agents as before (Claude Code, Codex, Cursor, OpenCode).
  6. `composer run dev`, open `/`, `/register` and `/login`.
  7. First commit. Push to the remote once D-01 names it.

  Fallback, only if the starter kit cannot be used: install `inertiajs/inertia-laravel`, `@inertiajs/react`, `react`, `react-dom`, `@vitejs/plugin-react` and `typescript` into the existing skeleton, then build the auth screens by hand on Fortify. This is slower and adds most of Phase 2's work.

  After the kit is in, read what it created (`routes/`, `resources/js/pages`, `app/Providers/FortifyServiceProvider.php`, `package.json` scripts) before changing anything.

- [ ] **1.2** Separate the environments.
  - `.env.example` holds every key with safe, non-secret values. Real secrets exist only on the server.
  - Set `APP_NAME=BrightLearners`.
  - Three environments: `local`, `staging`, `production`.
  - A variable that starts with `VITE_` is compiled into the public JavaScript. Never give a secret that prefix.

- [ ] **1.3** Configure MySQL, time zone and strictness.
  - `.env`: `DB_CONNECTION=mysql`, `DB_DATABASE=brightlearners`, charset `utf8mb4`.
  - `phpunit.xml`: `DB_CONNECTION=mysql`, `DB_DATABASE=brightlearners_testing`. Turn on `RefreshDatabase` in `tests/Pest.php` (it is commented out today).
  - Time zone: store everything in UTC (`APP_TIMEZONE=UTC`). Display and accept times in `Asia/Kolkata`; add `display_timezone` to `config/app.php`.
  - Keep MySQL strict mode on (`'strict' => true` in `config/database.php`).
  - In `AppServiceProvider::boot()`: `Model::shouldBeStrict(! app()->isProduction());` to catch lazy loading and silently discarded attributes during development.
  - Every migration has a working `down()` where practical.

- [ ] **1.4** Configure Redis for cache, queue and rate limiting.
  - `.env`: `REDIS_CLIENT=phpredis`, `CACHE_STORE=redis`, `QUEUE_CONNECTION=redis`.
  - Rate limiters use the cache store, so they move to Redis with it.
  - Check: `php artisan tinker --execute 'cache()->put("ping", 1, 10); dump(cache()->get("ping"));'`

- [ ] **1.5** Enable Inertia SSR and run it as its own supervised process.
  - The starter kit ships the SSR entry file and a build script. Find the script name in `package.json`.
  - Build, then start: `php artisan inertia:start-ssr`. On Windows, if that command has trouble, run `node bootstrap/ssr/ssr.js` directly.
  - Check that the HTML is rendered on the server: `curl -s http://localhost:8000/ | grep -i "<h1"`.
  - On the server the SSR process is supervised separately from PHP-FPM (see [../reference/deployment-runbook.md](../reference/deployment-runbook.md)).
  - Use `search-docs` with package `inertiajs/inertia-laravel` for the current SSR options.

- [ ] **1.6** Install Filament at `/admin` and keep the public out.
  ```bash
  composer require filament/filament:"^5.9" spatie/laravel-permission:"^8.3"
  php artisan filament:install --panels --no-interaction
  php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --no-interaction
  php artisan migrate
  ```
  - `User` implements Filament's `FilamentUser` and uses Spatie's `HasRoles`. `canAccessPanel()` returns true only for `super-admin` in this phase. Phase 2 widens it to all staff roles.
  - Do not enable Filament's registration page. Public `/register` creates a parent with no role, so a new sign-up can never reach `/admin`.
  - Use `search-docs` (packages `filament/filament`, `spatie/laravel-permission`) for the current install and panel-access APIs.

- [ ] **1.7** Add base packages only when justified. Spatie Permission is installed now because step 1.6 needs it. Scout waits for Phase 5. Meilisearch is not installed.

- [ ] **1.8** Create the domain folders: `app/Domains/{Accounts,Curriculum,Content,Catalog,Commerce,Payments,Access,Membership,Learning,Growth,Analytics}` with a `.gitkeep` in each, plus `app/Support`. The rules for them are in [../reference/architecture.md](../reference/architecture.md).

- [ ] **1.9** Configure the quality checks.
  - PHP: Pint is installed. Run `vendor/bin/pint --dirty --format agent` before every commit.
  - Static analysis: Larastan is optional and is a new dev dependency, so add it only if you want it.
  - Frontend: the starter kit ships ESLint, Prettier and a TypeScript check. Confirm the script names in `package.json`.

- [ ] **1.10** Create the CI pipeline in this order: install → lint and static checks → PHP tests → frontend build → SSR build.
  - The starter kit ships GitHub Actions workflows. Extend them; do not write new ones from scratch.
  - Add a MySQL 8 service and a Redis service to the test job.
  - The job fails if any step fails.

- [ ] **1.11** Prepare the staging deployment (needs D-01).
  - One scripted deploy: migrate, clear and warm caches, restart queue workers, restart SSR.
  - Follow the sequence in [../reference/deployment-runbook.md](../reference/deployment-runbook.md).
  - Staging mirrors production: same PHP and Node versions, same storage pattern, same queue and SSR layout.
  - Staging is blocked from search engines from day one (header `X-Robots-Tag: noindex` and basic auth or IP allow-list).

- [ ] **1.12** Create the health endpoint.
  - Laravel's `/up` already exists in `bootstrap/app.php`. Keep it as the liveness check.
  - Add `/health` for uptime monitors: it confirms the database and cache answer, and returns only `{"status":"ok"}` or a 503. No versions, paths or driver names.

## Data model

| Table | Purpose |
|---|---|
| Default migrations | `users`, `cache`, `jobs` and what the starter kit adds. Domain tables arrive in later phases. |
| Spatie tables | `roles`, `permissions` and pivots |
| `settings` | Optional key/value table. Create only if Phase 0 found business settings that must change at runtime. |

## Routes and screens

| Route | Purpose |
|---|---|
| `/` | Temporary landing page. Replaced by the real homepage in Phase 5. |
| `/admin` | Filament staff area |
| `/up`, `/health` | Health checks |

Do not design the homepage now. See the build rule in [00-master-plan.md](00-master-plan.md).

## Admin and operations

- [ ] Create one super-admin through a console command, not a public form: `php artisan make:command CreateSuperAdmin` (signature `staff:create-super-admin`). It asks for name, email and password, creates the user, marks the email verified and assigns `super-admin`.
- [ ] Turn on multi-factor authentication for the Filament panel before production. Check the current API with `search-docs`.

## Events, jobs and schedule

- [ ] Queue worker runs under Supervisor or systemd on the server. Locally: `php artisan queue:work` (already part of `composer run dev`).
- [ ] Scheduler: one cron entry running `php artisan schedule:run` every minute.
- [ ] Failed jobs: document the procedure — `queue:failed` to inspect, `queue:retry` to retry — and schedule `queue:prune-failed --hours=336`.

Horizon is the queue dashboard for staging and production. It needs `ext-pcntl` and `ext-posix`, which do not exist on Windows, so it is installed in [Phase 15](phase-15-hardening.md) and never run locally.

## Tests to write

| Required test | How |
|---|---|
| CI fails on a deliberately broken test | Push a failing test on a throwaway branch and confirm the pipeline goes red. Delete the branch. |
| SSR HTML contains server-rendered page content in staging | `curl` the staging homepage and check the heading text is in the response body. Add this to the deploy smoke test. |
| A queue test job executes | `tests/Feature/Foundation/QueueTest.php`: dispatch a small job and assert its effect. On staging, dispatch it once by hand and watch the worker. |
| A scheduler test task executes | Schedule a heartbeat command that writes a timestamp to the cache; check it on staging. |
| Admin requires authentication | `tests/Feature/Foundation/AdminAccessTest.php`: a guest is redirected to the admin login; a user without a role gets 403; a super-admin gets 200. |
| Production secrets are not in the frontend build | After `npm run build`, search `public/build` for secret names and values. Add the search to CI. |

## Exit checklist

- [ ] A fresh staging deployment succeeds from the repository alone.
- [ ] `php artisan migrate:fresh` and `php artisan migrate:rollback` both work.
- [ ] The queue worker and the SSR process restart by themselves after a server reboot.
- [ ] No feature depends on a local filesystem path.
- [ ] `php artisan test --compact` passes on MySQL.

## Risks and controls

| Risk | Control |
|---|---|
| Over-engineering | One Laravel application. No service split, Kubernetes or event bus. |
| SSR process instability | Supervise the Node SSR process, add health and logging, document the restart. |
| Environment drift | Scripted deployment and `.env.example`. Test on staging before production. |

## Not in this phase

- Business UI polish
- Payments
- Content management beyond the admin shell
- Mobile API
