# Deployment and production runbook

From Appendix H of the source plan. Staging is first deployed in [Phase 1](../plan/phase-01-engineering-foundation.md). This file is completed in [Phase 15](../plan/phase-15-hardening.md).

The hosting target is not chosen yet (decision D-01). The source plan describes a Linux server with Nginx. The repo also has the `deploying-to-cloud` skill for Laravel Cloud. Either can satisfy this runbook; pick one before Phase 1's staging step and adjust the commands below to it.

## Production topology

- [ ] Ubuntu / Linux server, or a managed equivalent
- [ ] Nginx or another reverse proxy
- [ ] PHP-FPM on the same PHP version as development (8.5)
- [ ] MySQL 8
- [ ] Redis
- [ ] A Node process for Inertia SSR
- [ ] Supervisor or systemd for Horizon / queue workers and SSR
- [ ] Private object storage, plus a CDN for public assets
- [ ] TLS / HTTPS
- [ ] Off-server backups

Staging uses the same topology, the same PHP and Node versions and the same storage pattern.

## Processes that must always be running

| Process | Command | Restart on deploy |
|---|---|---|
| Web | PHP-FPM | reload |
| Queue | `php artisan horizon` (or `queue:work` before Phase 15) | `php artisan horizon:terminate`, the supervisor starts it again |
| SSR | `php artisan inertia:start-ssr` | `php artisan inertia:stop-ssr`, the supervisor starts it again |
| Scheduler | cron: `* * * * * php artisan schedule:run` | — |

Each one is supervised and comes back by itself after a server reboot.

## Deployment sequence

1. Pull or release an immutable application version.
2. Install Composer dependencies in production mode: `composer install --no-dev --optimize-autoloader`.
3. Install and build frontend assets and the SSR bundle.
4. Put the site in maintenance mode only when the migration type requires it.
5. Run database migrations with explicit production confirmation: `php artisan migrate --force`.
6. Cache configuration, routes and views where applicable: `php artisan optimize`.
7. Restart PHP workers if needed.
8. Terminate / restart Horizon workers gracefully so new code loads.
9. Restart the Inertia SSR process.
10. Clear and warm application caches intentionally.
11. Run smoke tests: home, resource, product, login, admin, queue, payment endpoint health.
12. Exit maintenance mode.
13. Monitor logs, queue failures, payment errors and performance after release.

## Smoke test after every deploy

- [ ] `/up` and `/health` return 200
- [ ] Homepage HTML contains the main heading (SSR is working)
- [ ] One class page, one resource page, one product page load
- [ ] `/login` and `/admin` load
- [ ] A test job runs on the queue
- [ ] The Razorpay webhook endpoint answers (400 to an unsigned request is the correct answer)
- [ ] No new errors in the log

## Rollback readiness

- [ ] Keep the previous release artifact.
- [ ] Know whether each migration is safely reversible. Use a forward fix for destructive production migrations when a rollback risks data loss.
- [ ] Do not deploy code that needs a new schema before the schema is available, if a zero / low-downtime release is required.
- [ ] Take a database backup or snapshot before a high-risk migration.
- [ ] Document a feature flag or disable path for payment, membership release and publishing.

## Scheduled commands

Added phase by phase. Keep this list in step with `routes/console.php`.

| Command | Frequency | Phase |
|---|---|---|
| `queue:prune-failed --hours=336` | daily | 1 |
| `resources:publish-scheduled` | every 5 minutes | 4 |
| `products:publish-scheduled` | every 5 minutes | 6 |
| `orders:expire-pending` | every 5 minutes | 7 |
| `payments:reconcile` | every 10 minutes | 8 |
| `membership:release-weeks` | every 5 minutes | 10 |
| `membership:expire` | hourly | 10 |
| expiring-membership check | daily | 10 |
| sitemap generation | daily | 12 |
| `analytics:aggregate-daily` | nightly | 13 |
| prune raw analytics events | daily | 13 |

## Environment variables

`.env.example` lists every key. Secrets exist only on the server.

| Group | Keys | Phase |
|---|---|---|
| App | `APP_NAME`, `APP_ENV`, `APP_URL`, `APP_TIMEZONE` | 1 |
| Database | `DB_*` | 1 |
| Redis | `REDIS_*`, `CACHE_STORE`, `QUEUE_CONNECTION` | 1 |
| SSR | `INERTIA_SSR_ENABLED` and related | 1 |
| Mail | `MAIL_*` | 2 |
| Storage | bucket, region, endpoint and credentials for the `resources` and `previews` disks | 4 |
| Search | `SCOUT_DRIVER=database` | 5 |
| Razorpay | `RAZORPAY_KEY_ID`, `RAZORPAY_KEY_SECRET`, `RAZORPAY_WEBHOOK_SECRET` | 8 |
| Analytics | external analytics ID, Search Console verification | 12, 13 |

Nothing secret has a `VITE_` prefix.

## Indexing switch and launch SEO procedures

- **Staging / Non-Production Blocking**: Every non-production response is automatically appended with `X-Robots-Tag: noindex, nofollow` via `BlockIndexingOutsideProduction` middleware. Additionally, `/robots.txt` serves `User-agent: *\nDisallow: /` to block crawlers outside production.
- **Production Enabling**: Set `APP_ENV=production` in the production `.env`. This removes the `noindex` header and allows `/robots.txt` to permit crawling of public routes and advertise the sitemap index.
- **Sitemap Generation & Warmup**: Run `php artisan sitemap:generate` to pre-generate and warm the cache for `/sitemap.xml` and all segmented sitemaps (`/sitemaps/pages.xml`, `/sitemaps/resources.xml`, `/sitemaps/products.xml`, `/sitemaps/articles.xml`). The scheduler runs this command daily.
- **Google Search Console Verification**: Add `GOOGLE_SITE_VERIFICATION=your_verification_token` to production `.env` (configured in `config/services.php` as `services.google.site_verification`). The verification meta tag is rendered in the HTML head.
- **Sitemap Submission**: Submit `https://brightlearners.in/sitemap.xml` in Google Search Console after verification. Monitor index coverage and 404s weekly.

## Incident contacts

| Area | Role / Assignee | Escalation Channel | Response SLA |
|---|---|---|---|
| Application / Server Infrastructure | Engineering Lead / DevOps On-Call | alerts@brightlearners.in / DevOps Pager | 15 mins (P0) / 1 hr (P1) |
| Payments (Razorpay Account Owner) | Finance & Platform Admin | payments@brightlearners.in | 30 mins (P0 payment failure) |
| Content Corrections & Invalidation | Lead Content Editor | content-ops@brightlearners.in | 2 hrs |
| Customer Support & Refunds | Support Lead | support@brightlearners.in | 1 business day |

### Incident Classification & Triage
- **P0 (Critical Outage)**: Payment gateway down, private files leaking without signed URLs, checkout blocked, or site inaccessible. Immediate engineering escalation; put platform in maintenance mode (`php artisan down --secret=...`) if security is compromised.
- **P1 (Degraded Performance)**: High rate of 429s for genuine traffic, slow page loads (LCP > 3.5s), queue worker backlog growing (>1,000 jobs).
- **P2 (Minor / Functional)**: Isolated non-blocking UI issues, single resource preview generation retry.

## Database & File Backup Recovery Runbook

1. **Daily Automated Snapshots**: Managed daily database snapshot retained for 30 days off-server (Decision D-19).
2. **Snapshot Restore Procedure**:
   - Create a staging/recovery instance from the targeted daily snapshot point-in-time.
   - Run verification query: `SELECT count(*) FROM users; SELECT count(*) FROM orders;`.
   - Update DNS or database connection strings if initiating a full failover.
   - Run `php artisan migrate --force` if schema sync is required.
3. **Private Object Storage Lifecycle**: Versioning enabled on private `resources` bucket with 90-day non-current version expiration.

## Queue & Process Restart Procedures

- **Worker Graceful Restart**: Run `php artisan queue:restart`. Existing jobs complete; queue supervisors reload worker processes with updated code.
- **SSR Process Reload**: Run `php artisan inertia:stop-ssr`. Supervisor restarts the Node SSR server with fresh client bundles.
- **Cache Invalidation on Emergency Correction**:
  ```bash
  php artisan cache:clear
  # CurriculumTree cache flush
  php artisan tinker --execute 'App\Domains\Curriculum\Queries\CurriculumTree::flushCache();'
  ```

## Local development on Windows

| Need | How |
|---|---|
| Everything at once | `composer run dev` (server, queue listener, Vite) |
| Redis | Docker container `brightlearners-redis` |
| Queue | `php artisan queue:work` — Horizon cannot run on Windows |
| SSR | `php artisan inertia:start-ssr`, or `node bootstrap/ssr/ssr.js` |
| PDF previews | poppler via `scoop install poppler` |
| Webhooks | A tunnel to the local site, or the signed-payload tests |
