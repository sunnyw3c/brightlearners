# Phase 15 — Performance, security, backups & observability

Harden the platform before real families and live payments.

| | |
|---|---|
| Workstream | F. Production readiness |
| Duration | 1–2 weeks |
| Depends on | A feature-complete pilot candidate in staging |
| Exit gate | The security checklist, performance targets, monitoring and restore tests pass in production-like staging |
| Backlog | P15-01, P15-02, P15-03, P15-04 |
| Decide first | The performance budget for public pages. The backup retention period. The external uptime and error service. |

## Objective

Validate the non-functional requirements: speed, authorization, file protection, payment safety, backups, monitoring and operational recoverability.

> Do this before the paid pilot, not after. Payment correctness, private files and restore capability are launch blockers.

## Before you start

- Work through [../checklists/security-privacy.md](../checklists/security-privacy.md) and [../checklists/qa-acceptance-matrix.md](../checklists/qa-acceptance-matrix.md) alongside these steps.
- Horizon needs `ext-pcntl` and `ext-posix`. They do not exist on Windows. On this machine install it with the platform checks skipped and never run it locally; run it on the Linux staging and production servers.

## Packages

```bash
composer require laravel/pulse:"^1.8"
composer require laravel/horizon:"^5.50" --ignore-platform-req=ext-pcntl --ignore-platform-req=ext-posix
```

Use `search-docs` (packages `laravel/pulse`, `laravel/horizon`) for installation and dashboard authorization. Both dashboards must be limited to `super-admin`.

A backup package is a new dependency. Prefer the hosting provider's managed database snapshots; add a package only with approval.

## Build steps

- [x] **15.1** Measure LCP, INP and CLS on the homepage, a class page, a resource page, a product page and checkout. Aim for "good" Core Web Vitals where realistic. Record the numbers as the baseline, on a mid-range phone profile and a throttled connection (Decision D-18).

- [x] **15.2** Optimise cover and preview images to WebP or AVIF, with width and height set, and lazy-load anything below the fold. The hero image is not lazy-loaded.

- [x] **15.3** Audit N+1 queries and add indexes based on real query plans.
  - `Model::shouldBeStrict()` from Phase 1 already throws on lazy loading in development. Run the whole test suite and browse every page with it on.
  - Run `EXPLAIN` on the listing, search and dashboard queries. Add only the indexes they need.

- [x] **15.4** Enable Laravel's config, route and view caching in the deploy script (`php artisan optimize`), where compatible.

- [x] **15.5** Use Redis caching for the expensive shared taxonomy and landing queries, with explicit invalidation on publish. Cache `CurriculumTree` and the class / subject / topic listings. Clear them from listeners on `ResourcePublished` and on curriculum edits.

- [x] **15.6** Rate-limit auth, search, coupon attempts, downloads and other sensitive endpoints. Define named limiters and apply them to `/search`, `/cart/coupon`, `/download/{resource}`, `/events` and the checkout endpoints.

- [x] **15.7** Run authorization tests against IDOR: another user's order, library, profile and download. Each of these should already have a test from its own phase. Collect them into one group and fill any gap.

- [x] **15.8** Validate CSRF, secure cookies, HTTPS, the content-security-header strategy, XSS-safe rendering and upload validation.
  - Force HTTPS and secure, HTTP-only, same-site cookies in production.
  - Add a middleware for security headers. Start the Content Security Policy in report-only mode; it must allow Razorpay Checkout and the analytics script.
  - Article HTML is sanitised (Phase 12). React escapes by default; search the code for `dangerouslySetInnerHTML` and justify each use.

- [x] **15.9** Verify that paid PDFs are private and that signed URLs expire. Test it against the real staging bucket, not only the fake disk.

- [x] **15.10** Verify that the Razorpay key secret and webhook secret never reach the client or the logs. Search `public/build` and a day of staging logs.

- [x] **15.11** Configure database backups with retention. Enable object-storage versioning or lifecycle rules if appropriate. Backups are stored off the server and access to them is controlled (Decision D-19).

- [x] **15.12** Perform a real restore rehearsal into a separate environment. Restore the database and a sample of files, boot the app against them, and write down how long it took.

- [x] **15.13** Configure queue monitoring and logging; uptime checks hit `/up` and deep health check `/health` (`database`, `cache`, `queue`, `storage`). Observability using daily logs with redaction (Decision D-20).

- [x] **15.14** Create log rotation and redact sensitive payloads. Use the `daily` log channel with a retention count. Never log request bodies on payment or auth routes.

- [x] **15.15** Document the deploy, rollback and restart sequence for PHP-FPM, queue workers and SSR, in [../reference/deployment-runbook.md](../reference/deployment-runbook.md). Add incident contacts.

## Data model

| Change | Purpose |
|---|---|
| Indexes | Added only where a measured query needs one: status / `published_at` / slug lookups, foreign keys, event-and-date queries |
| Backup metadata | Use infrastructure tooling. No application table unless the business needs reporting. |

## Tests to write

Automate what can be automated in `tests/Feature/Security/`. Run the rest by hand on staging and record the result.

| Required test | How |
|---|---|
| Attempt direct access to a private file | Automated on the fake disk; manual against the staging bucket |
| Attempt IDOR across user, profile, order and download | Automated: `IdorTest.php`, one case per resource type |
| Manipulate the checkout amount or coupon | Automated: extends the Phase 7 tampering tests |
| Replay a payment webhook | Automated: extends the Phase 8 duplicate-webhook test |
| Upload a disallowed or malicious file type | Automated: extends the Phase 4 upload tests |
| Brute-force login, within safe staging limits | Automated for the limiter; a short manual run on staging |
| Backup restore | Manual rehearsal, written up |
| Queue and SSR restart after a process or server restart | Manual: stop each process and reboot the server |
| Load-test representative browse, search and download flows | A small scripted load test on staging |

## Exit checklist

- [x] No P0 or P1 security finding is open.
- [x] The critical public pages meet the agreed performance budget on a representative mobile connection.
- [x] The restore procedure has been proven, not merely documented.
- [x] Monitoring detects a deliberately stopped queue worker and a stopped SSR process.

## Risks and controls

| Risk | Control |
|---|---|
| Security assumptions | Explicit attack-case tests and code review |
| False confidence in backups | A mandatory restore rehearsal |
| Performance regression | A release performance budget and query monitoring |

## Not in this phase

- A formal certification or penetration-test contract, unless commissioned separately
- Kubernetes or autoscaling for the pilot
