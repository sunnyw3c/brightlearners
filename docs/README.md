# BrightLearners — implementation plan

This folder holds the full plan for building the BrightLearners children's learning platform, phase by phase. It is built from the *Laravel Master Implementation Plan* (version 1.0, 3 October 2026) and the UI mockup, and fitted to this repo.

## Start here

1. Read [plan/00-master-plan.md](plan/00-master-plan.md) once. It has the goal, the rules that never change, and the roadmap.
2. Read [reference/architecture.md](reference/architecture.md). It says where code goes and how it is written.
3. Open the phase you are about to work on, and follow it from top to bottom.

The phase to begin with is [Phase 1](plan/phase-01-engineering-foundation.md). [Phase 0](plan/phase-00-scope-freeze.md) is business and content work that runs alongside it.

## How to work a phase

1. **Check "Decide first".** Each phase names the decisions it needs. If one is still `Open` in [tracking/decisions.md](tracking/decisions.md), get it decided first.
2. **Do "Before you start".** These are the prerequisites and local tooling.
3. **Work the build steps in order.** Each step is numbered the same way as the source plan (for example 4.3), so you can cross-check against the PDF. Tick the box when it is done.
4. **Write the tests in "Tests to write".** A step is not done until its test passes.
5. **Tick the exit checklist.** A phase is complete only when every item passes in staging.
6. **Update the status board below and [tracking/backlog.md](tracking/backlog.md).**

If a step needs a business rule that is not written down, add a row to [tracking/decisions.md](tracking/decisions.md) and ask. Do not invent the behaviour.

To have Claude build a phase, say for example: *"Implement Phase 3 following docs/plan/phase-03-curriculum-taxonomy.md."*

## Status board

Update the Status column as phases move: `Not started` · `In progress` · `Done`.

| # | Phase | Duration | Status |
|---|---|---|---|
| 0 | [Business scope freeze & pilot readiness](plan/phase-00-scope-freeze.md) | 1–2 w | Not started |
| 1 | [Engineering foundation & environments](plan/phase-01-engineering-foundation.md) | 1 w | Not started |
| 2 | [Identity, security & roles](plan/phase-02-identity-security-roles.md) | 1 w | Not started |
| 3 | [Curriculum taxonomy & core domain model](plan/phase-03-curriculum-taxonomy.md) | 1–2 w | Not started |
| 4 | [Resource engine, versioning & review workflow](plan/phase-04-resource-engine.md) | 2 w | Not started |
| 5 | [Public learning library, SSR & search](plan/phase-05-public-library-ssr-search.md) | 2 w | Not started |
| 6 | [Product catalogue & merchandising](plan/phase-06-product-catalogue.md) | 1–2 w | Not started |
| 7 | [Cart, pricing, coupons & order state machine](plan/phase-07-cart-pricing-orders.md) | 1–2 w | Not started |
| 8 | [Razorpay payments, webhooks & refunds](plan/phase-08-razorpay-payments.md) | 1–2 w | Not started |
| 9 | [Entitlements, private downloads & My Library](plan/phase-09-entitlements-downloads-library.md) | 1–2 w | Not started |
| 10 | [Membership & weekly programme engine](plan/phase-10-membership-programme.md) | 2 w | Not started |
| 11 | [Parent dashboard, progress & notifications](plan/phase-11-parent-dashboard-notifications.md) | 1–2 w | Not started |
| 12 | [Parent Hub, SEO system & discoverability](plan/phase-12-parent-hub-seo.md) | 2 w | Not started |
| 13 | [Analytics, funnel & business KPIs](plan/phase-13-analytics-kpis.md) | 1 w | Not started |
| 14 | [Admin operations, corrections & support](plan/phase-14-admin-operations-support.md) | 1 w | Not started |
| 15 | [Performance, security, backups & observability](plan/phase-15-hardening.md) | 1–2 w | Not started |
| 16 | [Content loading, UAT, soft launch & paid pilot](plan/phase-16-content-uat-pilot.md) | 2–4 w + pilot | Not started |
| 17 | [Post-pilot learning intelligence](plan/phase-17-learning-intelligence.md) | 4–8 w | Not started |
| 18 | [Scale platform](plan/phase-18-scale-platform.md) | Ongoing | Not started |

## What is in this folder

| Folder | Contents |
|---|---|
| [plan/](plan/00-master-plan.md) | The master plan and one file per phase |
| reference/ | Things every phase looks up |
| checklists/ | Lists to tick before hardening and launch |
| tracking/ | The backlog and the decision register |
| design/ | The UI mockup |

### Reference

| File | Use it when |
|---|---|
| [architecture.md](reference/architecture.md) | Deciding where code goes and how to write it |
| [database-blueprint.md](reference/database-blueprint.md) | Writing a migration |
| [routes-and-screens.md](reference/routes-and-screens.md) | Adding a route or a page |
| [roles-and-permissions.md](reference/roles-and-permissions.md) | Adding an admin action or a policy |
| [design-reference.md](reference/design-reference.md) | Building any public or account screen |
| [commercial-config.md](reference/commercial-config.md) | Anything about prices, plans or licences |
| [analytics-events.md](reference/analytics-events.md) | Firing or reading an event |
| [content-production-sop.md](reference/content-production-sop.md) | Producing or reviewing learning content |
| [deployment-runbook.md](reference/deployment-runbook.md) | Deploying, scheduling, restarting |

### Checklists

| File | Used in |
|---|---|
| [qa-acceptance-matrix.md](checklists/qa-acceptance-matrix.md) | Every phase, and the full run before the pilot |
| [security-privacy.md](checklists/security-privacy.md) | Phase 15 |
| [seo.md](checklists/seo.md) | Phase 12 and before launch |
| [pilot-launch.md](checklists/pilot-launch.md) | Phase 16 go / no-go |

### Tracking

| File | Contents |
|---|---|
| [backlog.md](tracking/backlog.md) | All 77 backlog items with status |
| [decisions.md](tracking/decisions.md) | Open business decisions, mockup conflicts, technical defaults |

## Three things to know before Phase 1

1. **The repo is the plain Laravel skeleton.** Phase 1 moves it onto the official React starter kit. When you do that, copy this `docs/` folder and `.ai/` into the new project, or the plan is lost.
2. **Composer and the command line use different PHP versions today** (8.3 and 8.5). Phase 1 fixes this first.
3. **Do not start with the homepage.** Curriculum (Phase 3), resource versioning (Phase 4) and access (Phase 9) come first, because everything else depends on them.

## Keeping this plan honest

- These files are the working copy. When reality changes, change the file.
- A changed decision goes in [tracking/decisions.md](tracking/decisions.md) with a date.
- A new table or column goes in [reference/database-blueprint.md](reference/database-blueprint.md) in the same commit as its migration.
- A new scheduled command goes in [reference/deployment-runbook.md](reference/deployment-runbook.md).
