# Phase 0 — Business scope freeze & pilot readiness

Turn the business roadmap into a buildable, testable product baseline.

| | |
|---|---|
| Workstream | A. Product foundation |
| Duration | 1–2 weeks |
| Depends on | Commercial scope and pricing from the business plan. At least one person who can approve content, pricing, policies and launch readiness. |
| Exit gate | Scope baseline signed off. Initial content inventory and pilot metrics approved. |
| Backlog | P0-01, P0-02, P0-03 |
| Type of work | Business and content. No code. Engineering can start Phase 1 in parallel. |

## Objective

Freeze what the first paid pilot must do, identify the content that will fill it, define the parent journey, and explicitly defer anything that does not prove the business model.

## Deliverables

- [ ] Approved MVP scope and out-of-scope list
- [ ] Confirmed Classes 1–3 curriculum map at topic and skill level
- [ ] Content inventory: 12 topic packs, 2 flagship ebooks, 3 bundles, 15–20 free resources, 3 starter checks, one full membership month
- [ ] Pilot success scorecard with stop / continue criteria
- [ ] Licence, refund, correction and privacy policy decisions listed for legal and accounting review
- [ ] Brand basics: name, logo placeholder, tone, primary colour, domain

## Steps

Each step produces a short written record. Keep them in `docs/business/` (create the folder when the first one is written) and record every decision in [../tracking/decisions.md](../tracking/decisions.md).

- [ ] **0.1** Interview or validate the parent jobs-to-be-done: quick practice, gap support, enrichment, revision, routine building.
  Output: one page listing the jobs, ranked.
- [ ] **0.2** Define the exact pilot promise: short parent-led sessions, a clear objective, time, supplies and answer guidance.
  Output: one paragraph that will appear on `/membership`.
- [ ] **0.3** Approve launch classes and subjects: Classes 1–3; Maths, English, selected practical / EVS / mixed practice.
  The mockup also shows **Hindi** as a subject. Decide now whether Hindi is in the launch scope (decision M-01).
- [ ] **0.4** Freeze the product ladder: free → ₹79 topic pack → ₹149–199 workbook → ₹349–449 bundle → membership.
  Output: the launch SKU list in [../reference/commercial-config.md](../reference/commercial-config.md).
- [ ] **0.5** Confirm the membership weekly rhythm and what is excluded from membership during the pilot.
- [ ] **0.6** Decide which flagship ebooks are eligible for the member discount, and whether the 15% member discount stays in the pilot (decision D-04).
- [ ] **0.7** Inventory existing content. Classify every item as *ready*, *revise*, *separate collection* or *do not launch*.
  Output: the content inventory sheet with an owner and review status per item.
- [ ] **0.8** Define the pilot success thresholds: paid conversion, activation, weekly use, support load, refund rate, content error rate, renewal intent, production readiness.
  Output: the scorecard in [../reference/analytics-events.md](../reference/analytics-events.md) with a number against each row.
- [ ] **0.9** Create a content production calendar that stays at least one month ahead of member delivery. Follow [../reference/content-production-sop.md](../reference/content-production-sop.md).
- [ ] **0.10** List the policies that need professional review before launch: GST / invoicing classification, privacy and child-data wording, refund / cancellation, digital licence terms.

## Curriculum map format

Phase 3 seeds the database from this map, so write it in a form a developer can load. One row per skill:

| Class | Subject | Topic | Skill | Learning objective | Difficulty band |
|---|---|---|---|---|---|
| Class 2 | Maths | Addition and Subtraction | Add two 2-digit numbers without regrouping | Adds two 2-digit numbers where no column sums past 9 | core |

Topic names seen in the mockup for Class 2 Maths — Numbers up to 1000, Addition & Subtraction, Multiplication, Shapes & Geometry, Measurement, Time and Calendar, Data Handling — are illustrations, not an approved list. The teacher reviewer decides the real map.

## Operational control

- [ ] Keep [../tracking/decisions.md](../tracking/decisions.md) as the shared decision register. When a later phase needs a business rule that is not written down, the developer adds a question there instead of inventing the behaviour.

## Required testing

- [ ] Run 5 parent walkthroughs of the intended funnel before implementation: find a free resource → understand the skill → see the paid pack → understand membership. The mockup can be used as the prototype.
- [ ] The teacher reviewer confirms the launch skill map is right for the age and class.

## Exit checklist

- [ ] No unresolved P0 question affects checkout, access, pricing, membership length or what a learning resource means.
- [ ] The launch catalogue has owners and a review status.
- [ ] The pilot metrics can be measured by the events planned for [Phase 13](phase-13-analytics-kpis.md).

## Decisions this phase must close

These block later phases. Details are in [../tracking/decisions.md](../tracking/decisions.md).

| Needed before | Decision |
|---|---|
| Phase 1 staging | D-01 Hosting target and domain names |
| Phase 4 | D-02 Object storage provider, bucket and CDN |
| Phase 5 | D-03 Do free downloads need a login or email? |
| Phase 6 | D-04 Exact launch SKUs and member-discount eligibility |
| Phase 7 | D-05 GST / tax / invoice treatment |
| Phase 8 | D-06 Razorpay account ownership, settlement and refund permissions |
| Phase 9 | D-07 Can an expired member re-download old packs? |
| Phase 10 | D-08 Pilot start date and release calendar |

## Risks and controls

| Risk | Control |
|---|---|
| Scope creep | A written MVP / out-of-scope list. New features need a change request. |
| Empty-store launch | Content inventory and production run in parallel from this phase. |
| Unclear educational positioning | Every resource states class, skill, objective, duration and parent use. |
| Compliance uncertainty | Record the decisions that need CA / legal / privacy review before live payments. |

## Not in this phase

- Native mobile app, AI tutor, school ERP
- Teacher marketplace or live classes
- Student social or community features
