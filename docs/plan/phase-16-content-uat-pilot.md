# Phase 16 — Content loading, UAT, soft launch & paid pilot

Move from feature-complete software to a validated business pilot.

| | |
|---|---|
| Workstream | F. Production readiness |
| Duration | 2–4 weeks, then the 90-day pilot |
| Depends on | Phases 0–15 pass their gates. Live pricing and policies, and the accounting and privacy reviews, are complete. At least one future membership month is ready or in final review. |
| Exit gate | 20–30 paying families can use the product. Support, usage and economics are measured. |
| Backlog | P16-01, P16-02, P16-03, P16-04, P16-05 |
| Decide first | D-10 legal / privacy / policy review complete and a support owner assigned |
| Type of work | Mostly content and operations. Little new code. |

## Objective

Fill the real catalogue, run end-to-end acceptance, launch to a controlled cohort, and use the evidence to decide what to improve or expand.

> The pilot is not simply a beta test of code. It is a test of demand, the usefulness of the learning content, operational repeatability and unit economics.

## Before you start

- The go / no-go list is [../checklists/pilot-launch.md](../checklists/pilot-launch.md).
- Every item of content goes through the Phase 4 workflow. Nothing is loaded directly as "published".

## Steps

- [ ] **16.1** Load 12 focused topic packs: four each for Classes 1, 2 and 3.
- [ ] **16.2** Load two checked flagship ebooks and three bundles.
- [ ] **16.3** Publish 15–20 useful free resources and three class starter checks.
- [ ] **16.4** Load a complete membership month for each launch class, plus buffer content.
- [ ] **16.5** Review the puzzles, instructions and answers of *Maths Through Games* before positioning it as a flagship.
- [ ] **16.6** Keep *1000 Essential English Words* separate unless a specific child-level adaptation has been reviewed.
- [ ] **16.7** Run full UAT. Tick each flow on a phone and on a desktop:
  - [ ] registration and email verification
  - [ ] free download
  - [ ] search
  - [ ] product purchase
  - [ ] coupon
  - [ ] payment failure, then success
  - [ ] signed download
  - [ ] membership activation
  - [ ] weekly release
  - [ ] expiry simulation
  - [ ] refund
  - [ ] correction notice
  - [ ] every email
  - [ ] mobile flows
- [ ] **16.8** Launch in three steps: internal testers, then friendly families, then the paying pilot cohort.
- [ ] **16.9** Provide one support channel and log every issue by category and severity.
- [ ] **16.10** Review weekly: acquisition source, free downloads, conversion, activation, weekly use, support effort, content errors, refunds, payment failures and next-month readiness.
- [ ] **16.11** Collect structured parent feedback after representative activities. Separate usability problems from learning-content problems.
- [ ] **16.12** At 90 days, review actual revenue and costs, repeat use, content production reliability, support burden and renewal intent before activating regular membership.

## Small build items

| Item | Notes |
|---|---|
| Pilot cohort filter and dashboard | Extends the Phase 13 cohort view. A filter on subscriptions with plan `pilot_90`. |
| Flag a confusing resource and suspend it from new sales without deleting history | An admin action that archives the product or resource. Existing owners keep access. |
| Feedback tagging by content / UX / payment / support | The optional `feedback` table, or a shared sheet if volume is small |

## Events

`PilotStarted`, `FeedbackSubmitted` (optional), `PilotCompleted` / membership expiry.

## Required testing

- [ ] A production smoke test using the smallest safe real transaction, if business policy permits. Refund it afterwards.
- [ ] Mobile devices across the common browsers.
- [ ] Emails from the production domain reach the inbox and contain the correct links.
- [ ] Every published file opens and prints correctly.
- [ ] No staging URL appears in production metadata or emails.

## Exit checklist

- [ ] 20–30 paying families are recruited, or there is a documented reason if acquisition itself fails.
- [ ] Families can understand the instructions and use the resources repeatedly.
- [ ] Support is dependable and measurable.
- [ ] Next month's content can be delivered without a last-minute production dependency.
- [ ] Management explicitly chooses: iterate, expand, change price, or stop / pause.

## Weekly pilot review template

Copy this into a dated note each week.

| Area | This week | Last week | Note |
|---|---|---|---|
| Visits → free downloads | | | |
| Free download → purchase | | | |
| Pilot members (active) | | | |
| Members who opened this week's pack | | | |
| Packs completed | | | |
| Support contacts and minutes | | | |
| Content errors reported | | | |
| Refunds | | | |
| Payment failures | | | |
| Next month ready? | | | |

## Risks and controls

| Risk | Control |
|---|---|
| Too broad a public launch | A cohort / soft launch and controlled marketing |
| Content-quality incidents | The fast correction and version workflow, with notification |
| Misreading early vanity traffic | Use paid, repeat-use, renewal and contribution metrics |
| Hidden founder workload | Track support and content hours to estimate the real economics |

## Not in this phase

- Class 4 / 5 expansion before the pilot review
- A native app
- A teacher-licence rollout, unless the pilot explicitly validates the need
