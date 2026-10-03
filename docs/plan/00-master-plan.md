# BrightLearners — Master Plan

Source: *Children's Learning Platform — Laravel Master Implementation Plan*, version 1.0, 3 October 2026 (91 pages, Phases 0–18, Appendices A–O), plus the BrightLearners UI mockup in [../design/ui-mockup.png](../design/ui-mockup.png).

This file is the overview. The work itself is in the phase files listed in the [roadmap](#roadmap).

## What we are building

A learning website for Indian parents of children in Classes 1–3. Parents find free worksheets, buy one-time products (topic packs, workbooks, ebooks, bundles) and can join a prepaid 90-day family membership that releases four weekly packs per class per month.

| Launch focus | Decision |
|---|---|
| Market | India first. The parent or guardian is the account holder. |
| Learners | Classes 1–3 for the paid pilot |
| Business model | Free resources + one-time products + family membership |
| Pilot | 90 days, ₹499 prepaid, target 20–30 paying families |
| Membership delivery | Four weekly packs per class per month, a monthly skill check and parent guidance |
| Architecture | One Laravel application. No microservices for the MVP. |

## Technology baseline

| Layer | Choice |
|---|---|
| Backend | Laravel 13, PHP 8.5, modular monolith |
| Frontend | React 19 + TypeScript through Inertia 3, server-side rendered (SSR) |
| Styling | Tailwind CSS 4 |
| Database | MySQL 8 |
| Cache, queue, rate limits | Redis |
| Admin | Filament 5 at `/admin` |
| Files | Private object storage for paid PDFs, public storage/CDN for previews |
| Payments | Razorpay |
| Search | Laravel Scout, database driver (Meilisearch only if needed later) |
| Tests | Pest 4 |

## Architecture rules that must stay true

These ten rules come from the source plan. Every phase file assumes them.

1. **Modular monolith.** One Laravel app with clear domain folders. No microservices.
2. **SEO-first rendering.** Public discovery pages are server-rendered through Inertia SSR and return real HTML before any JavaScript runs.
3. **Resource ≠ Product.** A resource is reusable learning content. A product is a commercial package that points at one or more resources. Products never own a copy of a PDF.
4. **Central entitlements.** Every "can this user open this file?" decision goes through one access service. Controllers never repeat purchase logic.
5. **Private files.** Paid PDFs and answer keys never have a permanent public URL.
6. **Server-authoritative commerce.** Price, discount, tax, totals and payment state are calculated and verified on the server.
7. **Versioned content.** Published PDFs are versioned and reviewed. A correction creates a new version; it never overwrites history.
8. **Parent-controlled learner profile.** Children do not have accounts. Collect only nickname, class and optional avatar/interests.
9. **Queues for slow work.** Email, file processing, indexing, aggregation and webhook side-effects run on the queue.
10. **Measurable pilot.** Free download, purchase, member activation, weekly use, support and renewal signals are tracked before any expansion.

## Business requirements

| ID | Requirement | What it means for the build |
|---|---|---|
| BR-01 | Free resources | Worksheets with answers, starter checks, sample pages, parent guide, weekly activity |
| BR-02 | Paid products | Topic packs, workbooks, ebooks, revision/holiday packs, flashcards/games, bundles |
| BR-03 | Family membership | Four weekly packs per class per month, mixed practice, stories/quizzes where suitable, monthly skill check, parent guidance |
| BR-04 | Classes | Classes 1–3 first. Class 4/5 and early years only after validation. |
| BR-05 | Launch catalogue | 12 topic packs, 2 checked flagship ebooks, 3 bundles, 15–20 free resources, 3 starter checks, 1 complete membership month |
| BR-06 | Content QA | AI may help draft. A qualified teacher reviews objectives, difficulty and instructions. Every answer is solved independently. Print and language checks are required. |
| BR-07 | Versioning | Version number, review date, correction log, and a notice to customers for material corrections |
| BR-08 | Pricing | Topic pack ₹79; workbooks ₹149–199; activity ebook ₹199–299; holiday pack ₹249; bundles ₹349–449; starter bundle ₹499–699. All are test prices. |
| BR-09 | Pilot | 90-day prepaid pilot at ₹499. No automatic renewal. 20–30 paying families. |
| BR-10 | Later membership pricing | Monthly ₹199, quarterly ₹549, annual ₹1,799 as first test points |
| BR-11 | Licensing | Household-use licence. No redistribution, resale or public upload. Teacher licence later. |
| BR-12 | Account library | Parents can reach purchases and eligible member downloads. New member access stops when membership ends, as the terms state. |
| BR-13 | Website | Home, browse by class/subject, free resources, shop/product pages, membership, learning library, parent guides, help/policies |
| BR-14 | Marketing journey | Free activity → class starter pack → paid workbook → membership. Show real pages and real learning objectives. |
| BR-15 | Existing books | *Maths Through Games* may become a checked flagship product. *1000 Essential English Words* stays separate unless adapted for Classes 1–5. |

The earlier commercial plan recommended Shopify. Laravel replaces that choice. The business model, catalogue, prices, pilot controls and validation gates are unchanged.

## Roadmap

A phase is complete only when its exit checklist passes in staging. Content production runs in parallel with engineering from Phase 0, so the pilot does not launch with empty categories.

| # | Phase | Group | Duration | Exit gate |
|---|---|---|---|---|
| 0 | [Business scope freeze & pilot readiness](phase-00-scope-freeze.md) | A. Product foundation | 1–2 w | Approved scope, content inventory and pilot metrics |
| 1 | [Engineering foundation & environments](phase-01-engineering-foundation.md) | A | 1 w | App deploys to staging with SSR, database, Redis and admin shell |
| 2 | [Identity, security & roles](phase-02-identity-security-roles.md) | A | 1 w | Parent auth, staff roles and learning profiles work |
| 3 | [Curriculum taxonomy & core domain model](phase-03-curriculum-taxonomy.md) | A | 1–2 w | Class/subject/topic/skill model seeded and governed |
| 4 | [Resource engine, versioning & review workflow](phase-04-resource-engine.md) | B. Content platform | 2 w | Resources can be created, reviewed, versioned and published |
| 5 | [Public learning library, SSR & search](phase-05-public-library-ssr-search.md) | B | 2 w | Indexable class/topic/resource pages work |
| 6 | [Product catalogue & merchandising](phase-06-product-catalogue.md) | C. Commerce | 1–2 w | Packs, workbooks and bundles are represented cleanly |
| 7 | [Cart, pricing, coupons & order state machine](phase-07-cart-pricing-orders.md) | C | 1–2 w | Server-authoritative checkout preparation is complete |
| 8 | [Razorpay payments, webhooks & refunds](phase-08-razorpay-payments.md) | C | 1–2 w | Payment lifecycle is reliable and idempotent |
| 9 | [Entitlements, private downloads & My Library](phase-09-entitlements-downloads-library.md) | C | 1–2 w | Access is centralised and paid files are protected |
| 10 | [Membership & weekly programme engine](phase-10-membership-programme.md) | D. Membership | 2 w | The 90-day prepaid pilot can be sold and scheduled |
| 11 | [Parent dashboard, progress & notifications](phase-11-parent-dashboard-notifications.md) | D | 1–2 w | Members see current learning and basic progress |
| 12 | [Parent Hub, SEO system & discoverability](phase-12-parent-hub-seo.md) | E. Growth engine | 2 w | Organic acquisition foundations are complete |
| 13 | [Analytics, funnel & business KPIs](phase-13-analytics-kpis.md) | E | 1 w | The pilot can be measured objectively |
| 14 | [Admin operations, corrections & support](phase-14-admin-operations-support.md) | E | 1 w | Business team operates without a developer |
| 15 | [Performance, security, backups & observability](phase-15-hardening.md) | F. Production readiness | 1–2 w | Non-functional requirements pass |
| 16 | [Content loading, UAT, soft launch & paid pilot](phase-16-content-uat-pilot.md) | F | 2–4 w + 90-day pilot | The 20–30 family pilot is running |
| 17 | [Post-pilot learning intelligence](phase-17-learning-intelligence.md) | G. Post-pilot | 4–8 w | Assessments, skill scoring, recommendations, recurring billing |
| 18 | [Scale platform](phase-18-scale-platform.md) | G | Ongoing | Each expansion has its own business case and gate |

Durations are the source plan's estimate for one experienced full-stack developer with part-time content and teacher support. Adding the ranges for Phases 0–16 gives **22–32 weeks to pilot launch**, then the 90-day pilot. Re-forecast from real velocity after Phase 4.

## Build order

```
Scope & content readiness            (Phase 0)
        ↓
Laravel foundation                   (1)
        ↓
Identity & roles                     (2)
        ↓
Curriculum taxonomy                  (3)
        ↓
Resource engine & review workflow    (4)
        ↓
Public SEO learning library          (5)
        ↓
Products → cart → orders → Razorpay  (6, 7, 8)
        ↓
Entitlements → private downloads → My Library   (9)
        ↓
Prepaid membership → weekly programme → dashboard   (10, 11)
        ↓
Parent Hub + SEO + analytics + operations   (12, 13, 14)
        ↓
Security / performance / backups / UAT      (15, 16)
        ↓
Soft launch → 20–30 paying families → 90-day review
        ↓
Assessments / recommendations / recurring billing, only if the evidence supports them   (17)
        ↓
More classes, teacher/B2B, interactive learning, AI and apps   (18)
```

> **Non-negotiable build rule.** Do not start by polishing the homepage while the curriculum, resource-versioning and entitlement models are undefined. Those three foundations decide whether the platform stays maintainable when the catalogue and membership grow.

## Where the repo stands today

Checked on 3 October 2026.

| Area | Today | Target | Closed in |
|---|---|---|---|
| Framework | Laravel 13.34 skeleton, untouched | Same, with domain folders | Phase 1 |
| Frontend | Blade `welcome` page, plain JS, Tailwind 4, Vite 8 | Inertia 3 + React 19 + TypeScript + SSR | Phase 1 |
| Auth | `User` model only, no login screens | Register, login, verify, reset, 2FA | Phase 1–2 |
| Admin | None | Filament 5 at `/admin` | Phase 1 |
| Database | SQLite | MySQL 8 (8.0.39 is installed locally) | Phase 1 |
| Cache/queue | `database` drivers | Redis (no Redis server installed yet) | Phase 1 |
| Roles | None | Spatie Permission, 7 staff roles | Phase 2 |
| Version control | Not a git repository | Git + CI | Phase 1 |
| Tests | Pest 4 with two example tests, SQLite in memory | Pest on MySQL | Phase 1 |
| PHP | `php` is 8.5.7, but Composer runs on PHP 8.3.31 (Herd) | One version everywhere | Phase 1 |
| PDF preview tooling | No Ghostscript, Imagick or poppler | poppler `pdftoppm` | Phase 4 |

The full list of repo-specific choices, with reasons, is in [../tracking/decisions.md](../tracking/decisions.md) under "Technical defaults".

## Pinned versions

Confirmed against Packagist and npm on 3 October 2026. All support Laravel 13. Re-check with `composer show <package>` when the phase starts.

| Package | Version | First used |
|---|---|---|
| `laravel/framework` | 13.34 (installed) | — |
| `laravel/react-starter-kit` | `main` branch, requires Laravel ^13.17 | Phase 1 |
| `inertiajs/inertia-laravel` | 3.5.1 | Phase 1 |
| `@inertiajs/react` | 3.8.0 | Phase 1 |
| `react`, `react-dom` | 19.3 | Phase 1 |
| `typescript` | 7.0 | Phase 1 |
| `laravel/fortify` | 1.40.0 | Phase 1 |
| `filament/filament` | 5.9.0 (uses Livewire ^4.4) | Phase 1 |
| `spatie/laravel-permission` | 8.3.0 | Phase 2 |
| `laravel/scout` | 11.8.0 | Phase 5 |
| `razorpay/razorpay` | 2.9.3 | Phase 8 |
| `spatie/laravel-sitemap` | 8.2.0 (needs PHP ^8.4) | Phase 12 |
| `laravel/pulse` | 1.8.1 | Phase 15 |
| `laravel/horizon` | 5.50.0 (Linux only: needs `ext-pcntl`, `ext-posix`) | Phase 15 |

## Related documents

- How the code is organised: [../reference/architecture.md](../reference/architecture.md)
- Every table: [../reference/database-blueprint.md](../reference/database-blueprint.md)
- Every route and screen: [../reference/routes-and-screens.md](../reference/routes-and-screens.md)
- Open and decided questions: [../tracking/decisions.md](../tracking/decisions.md)
- Backlog with status: [../tracking/backlog.md](../tracking/backlog.md)
