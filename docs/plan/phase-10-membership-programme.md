# Phase 10 — Membership & weekly programme engine

Implement the 90-day prepaid family pilot and the scheduled monthly learning programme.

| | |
|---|---|
| Workstream | D. Membership |
| Duration | ~2 weeks |
| Depends on | The entitlement engine works (Phase 9). At least one complete membership month is reviewed and ready. |
| Exit gate | A parent can buy the pilot and receive class-appropriate weekly packs on schedule |
| Backlog | P10-01, P10-02, P10-03, P10-04 |
| Decide first | D-08 pilot start date and release calendar. D-04 member discount. D-07 re-download after expiry. D-17 what happens when a member buys again. |

## Objective

Build membership as a structured routine, not an unlimited file dump. Start with a prepaid 90-day access period, so recurring-payment complexity stays out of the validation stage.

## Before you start

- The pilot is sold through the normal cart, order and Razorpay flow as a product of type `membership`. No second checkout is built.
- Programmes are calendar-based: one programme per class per month, four weeks each, every week with its own release time. All members of a class see the same released weeks.
- Mockup screen: "Weekly Learning Programme (90 Days)" with ₹499, "Join Now" and the four "How It Works" steps.

## Commands

```bash
php artisan make:model App/Domains/Membership/Models/SubscriptionPlan --no-interaction
php artisan make:model App/Domains/Membership/Models/Subscription --no-interaction
php artisan make:model App/Domains/Membership/Models/SubscriptionEvent --no-interaction
php artisan make:model App/Domains/Membership/Models/LearningProgram --no-interaction
php artisan make:model App/Domains/Membership/Models/LearningWeek --no-interaction
php artisan make:model App/Domains/Membership/Models/LearningItem --no-interaction
php artisan make:migration create_membership_tables --no-interaction
php artisan make:seeder SubscriptionPlanSeeder --no-interaction
php artisan make:class App/Domains/Membership/Actions/ActivateMembership --no-interaction
php artisan make:class App/Domains/Membership/Actions/ReleaseLearningWeek --no-interaction
php artisan make:command ReleaseLearningWeeks --no-interaction
php artisan make:command ExpireMemberships --no-interaction
```

## Build steps

- [ ] **10.1** Create the plan model with pilot, monthly, quarterly and annual definitions. Activate only the pilot.

  | Code | Price (paise) | Length | Active |
  |---|---|---|---|
  | `pilot_90` | 49900 | 90 days | yes |
  | `monthly` | 19900 | 1 month | no |
  | `quarterly` | 54900 | 3 months | no |
  | `annual` | 179900 | 12 months | no |

  Create one product of type `membership` linked to `pilot_90` through `products.subscription_plan_id`. A cart can hold one membership product, and not while the user already has an active membership (D-17).

- [ ] **10.2** On pilot purchase, create the membership with start date, end date and status. `auto_renew` is false.
  - `ActivateMembership` runs from a listener on `OrderPaid` when an order item is a membership product.
  - `starts_at` is the payment time, or the pilot start date from D-08 if that is later. `ends_at` is `starts_at` plus the plan length.
  - The unique index on `subscriptions.source_order_id` guarantees one membership per order, however many times the listener runs.
  - Write a `subscription_events` row and fire `MembershipActivated`.

- [ ] **10.3** Create `learning_programs` by class and month, and `learning_weeks` 1–4.

- [ ] **10.4** Attach resources to weekly learning items in a deliberate order (`learning_items.sort_order`), with optional parent guidance text per item.

- [ ] **10.5** Support `release_at`, so the next week or month can be prepared early and released on time.
  - `membership:release-weeks` runs every 5 minutes. It picks weeks whose `release_at` has passed and `released_at` is empty.
  - `ReleaseLearningWeek` releases a week only if the programme is published and every item's resource has a published current version. It sets `released_at` and fires `LearningWeekReleased`.
  - A week that is due but not ready is not released. It appears on the admin dashboard as blocked.

- [ ] **10.6** Create the membership access rule: an active membership grants eligible current resources; flagship products stay excluded unless the plan explicitly includes them.

  Implement the `MembershipAccess` contract from Phase 9. A resource is allowed when all of these are true:
  1. it is an item in a week that has been released;
  2. that week's programme is for a class of one of the user's active learning profiles;
  3. the user has a membership that is active now;
  4. the week was released during the membership, or belongs to the programme month that was current when the membership started.

  If D-07 allows re-download after expiry, condition 3 becomes "has or had a membership" for weeks released during it.

  Membership grants only what is in a released week. A flagship ebook is never a learning item, so membership never unlocks it.

- [ ] **10.7** Apply the member discount on eligible one-time products only while the membership is active and the pricing rule allows it. Implement the `MembershipChecker` contract from Phase 7. The percentage comes from the plan's `benefits`.

- [ ] **10.8** Implement the membership expiry job and status changes.
  - `membership:expire` runs hourly: active memberships past `ends_at` become `expired`, with an event row and `MembershipExpired`.
  - `MembershipExpiring` fires a configured number of days before the end (suggested 14 and 3).

- [ ] **10.9** Create the admin month-at-a-glance view: for each class and week, the number of items, each item's review status, the release time, and a ready / blocked indicator.

- [ ] **10.10** Require next-month readiness before the current month's final week, wherever operations demand it. A config switch makes this a warning or a hard block on releasing week 4.

- [ ] **10.11** Queue the weekly and new-month notifications. This phase fires the events; the emails and the dashboard are built in Phase 11.

## Data model

Column detail is in [../reference/database-blueprint.md](../reference/database-blueprint.md#membership--phase-10).

| Table | Purpose |
|---|---|
| `subscription_plans` | Pilot, monthly, quarterly, annual definitions |
| `subscriptions` | A user's membership term and status |
| `subscription_events` | Activation, expiry, cancellation, renewal history |
| `learning_programs` | Class + month programme |
| `learning_weeks` | Weeks 1–4 and the release schedule |
| `learning_items` | Ordered resources in a week |

## Routes and screens

| Route | Purpose | Inertia page |
|---|---|---|
| `/membership` | Membership landing | `membership/index` |
| `/membership/sample-month` | A public, representative programme month | `membership/sample-month` |
| `/account/membership` | Membership status and history | `account/membership` |
| `/account/weekly-learning` | Current released programme content | `account/weekly-learning` |

`/membership` must state inclusions, exclusions, duration and what happens at the end: no automatic renewal, and the re-download rule from D-07.

## Admin (Filament)

Navigation group: Membership.

- Plan pricing can be edited by `business-admin` only.
- Programme completeness indicators: every week has its required, reviewed resources.
- A week with an unapproved resource version cannot be scheduled for release.

## Events, jobs and schedule

Events: `MembershipActivated`, `MembershipExpiring`, `MembershipExpired`, `LearningWeekReleased`, `LearningMonthPublished`.

| Schedule | Command |
|---|---|
| Every 5 minutes | `membership:release-weeks` |
| Hourly | `membership:expire` |
| Daily | expiring-soon check |

## Tests to write

All in `tests/Feature/Membership/`. Use time travel for anything involving dates.

| Required test | Case |
|---|---|
| A pilot purchase creates exactly one membership | The listener run twice creates one row |
| Membership dates equal the purchased term | `ends_at - starts_at` is exactly 90 days; the D-08 start date is respected |
| A Class 2 profile receives the Class 2 programme, not Class 1 | Access is allowed for Class 2 items and denied for Class 1 items |
| A future week cannot be accessed before release, except admin preview | Denied before `release_at`; allowed after the release job runs |
| An excluded flagship product is not granted by membership | A member is denied a flagship resource |
| The member discount applies only while active | Discount present for an active member, absent after expiry and for non-members |
| An expired member cannot access new releases | A week released after `ends_at` is denied |
| A permanent purchase stays accessible after membership expires | Completes the Phase 9 test |
| A week with an unpublished resource is not released | The release job leaves it blocked |
| Full lifecycle | Buy → activate → four releases → expiring notice → expire, in one time-travel test |

## Exit checklist

- [ ] The complete 90-day membership lifecycle passes with time-travel tests.
- [ ] One full month for Classes 1–3 can be scheduled and released.
- [ ] The membership page clearly states inclusions, exclusions, duration and renewal behaviour.

## Risks and controls

| Risk | Control |
|---|---|
| Subscription complexity too early | The pilot is prepaid one-time access. Recurring billing is deferred. |
| Late content | One-month buffer and the programme completeness dashboard |
| Membership seen as a file library | The weekly routine and parent guidance are the primary UI |

## Not in this phase

- Recurring mandate billing
- Teacher memberships
- An adaptive, personalised weekly plan
