# Phase 13 — Analytics, funnel & business KPIs

Instrument the pilot so business decisions use real behaviour, not impressions.

| | |
|---|---|
| Workstream | E. Growth engine |
| Duration | ~1 week |
| Depends on | The core flows exist end to end |
| Exit gate | Acquisition, commerce and learning events flow into a usable funnel dashboard |
| Backlog | P13-01, P13-02, P13-03, P13-04 |
| Decide first | The thresholds on the pilot scorecard (Phase 0, step 0.8). The data-retention period. |

## Objective

Create first-party event tracking, with an external analytics tool as a secondary view, sufficient to answer whether parents discover, download, buy, use and intend to continue.

> Do not make launch decisions from pageviews alone. The business must know whether free discovery converts into repeated learning and paid retention.

## Before you start

- The event dictionary and the pilot scorecard are in [../reference/analytics-events.md](../reference/analytics-events.md). This phase implements that file exactly.
- Most of the domain events already exist from earlier phases (`OrderPaid`, `MembershipActivated`, `ResourceDownloaded`, `ResourceCompleted`). This phase adds listeners. It does not rewrite those flows.

## Commands

```bash
php artisan make:model App/Domains/Analytics/Models/AnalyticsEvent --no-interaction
php artisan make:model App/Domains/Analytics/Models/DailyMetric --no-interaction
php artisan make:migration create_analytics_tables --no-interaction
php artisan make:enum App/Domains/Analytics/Enums/EventName --string --no-interaction
php artisan make:class App/Domains/Analytics/Services/Analytics --no-interaction
php artisan make:class App/Domains/Analytics/Actions/AggregateDailyMetrics --no-interaction
php artisan make:command AggregateDailyMetricsCommand --no-interaction
php artisan make:controller Analytics/ClientEventController --no-interaction
```

## Build steps

- [ ] **13.1** Define immutable event names and their required properties before instrumenting anything.
  - `EventName` is an enum. A name is never renamed or reused with a different meaning.
  - `Analytics::track($name, $properties, $dedupeKey = null)` rejects a call that is missing a required property.

- [ ] **13.2** Track `resource_view`, `resource_preview`, `free_download`, `product_view`, `add_to_cart`, `checkout_started`, `purchase_completed`, `payment_failed`, `membership_view`, `membership_started`, `resource_opened`, `resource_downloaded`, `resource_completed` and `search_performed`. Assessment events are added in Phase 17.

- [ ] **13.3** Include an anonymous or session ID before login, and the user ID only where the policy allows it. Do not store child-identifying data.
  - The anonymous ID is a random UUID in a first-party cookie. No fingerprinting.
  - Events carry `profile_id` as a number. They never carry a nickname.
  - On login, later events carry both IDs so a journey can be joined.

- [ ] **13.4** Dispatch authoritative business events on the server, and UI engagement events from the client where that is the only place they can be seen.
  - Server: listeners on existing domain events write `purchase_completed`, `membership_started`, `free_download`, `resource_downloaded` and the rest marked "Server" in the dictionary.
  - Client: `POST /events` accepts only a short allow-list (`resource_preview`, `membership_view`), validates properties, and is rate-limited.
  - Writes go through the queue.

- [ ] **13.5** Create daily aggregation jobs for dashboard metrics, so dashboards do not run expensive ad-hoc queries.
  - `analytics:aggregate-daily` runs once a night and writes `daily_metrics`.
  - It can be re-run for any date and produces the same rows.

- [ ] **13.6** Define the funnel: visitor → free resource → product → purchase → membership → weekly use. Each step is one named metric with one definition.

- [ ] **13.7** Calculate average order value, paid conversion, active members, member activation, repeat weekly use, refund rate, support rate and the renewal-intent signal.
  - Revenue figures are computed from `orders` and `payments`, not from events, so they reconcile with money actually received.

- [ ] **13.8** Create the pilot cohort view for the 20–30 families: one row per family with joined date, weeks active, packs opened and completed, purchases, support contacts.

- [ ] **13.9** Document the metric definitions so dashboards do not change meaning silently. Write each definition into [../reference/analytics-events.md](../reference/analytics-events.md) next to its metric key.

**External analytics.** Add GA4 (or an equivalent) as a secondary view. Its ID comes from config and the script loads only in production. It does not replace the first-party store, and it receives no learner detail.

**Retention.** Add a pruning rule for raw `analytics_events` older than the agreed period. `daily_metrics` is kept.

## Data model

Column detail is in [../reference/database-blueprint.md](../reference/database-blueprint.md#growth-and-operations--phase-12-and-13).

| Table | Purpose |
|---|---|
| `analytics_events` | First-party events |
| `daily_metrics` | Aggregated KPI snapshots |
| `acquisition_sources` | Optional. UTM and source attribution if needed. |

## Routes

| Route | Purpose |
|---|---|
| `POST /events` | Client engagement events, allow-listed and rate-limited |

## Admin (Filament)

Navigation group: System → Analytics. In the mockup this is the "Analytics" menu item.

- The business dashboard shows headline metrics with drill-downs.
- Raw event export is restricted to `analytics.export`.
- No personally sensitive learner detail appears in a broad dashboard.

## Events, jobs and schedule

| Schedule | Command |
|---|---|
| Nightly | `analytics:aggregate-daily` (the source plan calls this `NightlyAggregateMetrics`) |
| Daily | prune raw events past the retention period |

Purchase and membership events are recorded on the server.

## Tests to write

| Required test | File and cases |
|---|---|
| The purchase event fires once despite webhook retries | `tests/Feature/Analytics/PurchaseEventTest.php` — callback, then webhook, then the webhook again → one `purchase_completed` (the `dedupe_key` is the order ID) |
| A free-download event does not duplicate because of a page re-render | `tests/Feature/Analytics/FreeDownloadEventTest.php` — the event is tied to the download request, not to a page view |
| Dashboard totals reconcile to orders and payments | `tests/Feature/Analytics/RevenueReconciliationTest.php` — aggregated revenue equals the sum of captured payments minus refunds |
| The member count separates active from expired | `tests/Feature/Analytics/MemberCountTest.php` — time travel across an expiry |
| The client endpoint accepts only allow-listed events | `tests/Feature/Analytics/ClientEventTest.php` — a server-only event name is refused; the rate limit triggers |
| Aggregation is repeatable | `tests/Feature/Analytics/AggregationTest.php` — running a date twice gives the same rows |

## Exit checklist

- [ ] The pilot scorecard can be produced without manual database work.
- [ ] Revenue metrics reconcile with payment records.
- [ ] The analytics definitions are documented.

## Risks and controls

| Risk | Control |
|---|---|
| Vanity metrics | Prioritise conversion, repeat use, contribution and renewal signals |
| Duplicate events | Idempotency keys for authoritative server events |
| Privacy creep | A minimal event payload and a retention policy |

## Not in this phase

- An enterprise BI warehouse
- Predictive machine-learning analytics
