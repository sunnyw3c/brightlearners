# Phase 18 — Scale platform

Expand curriculum, customer types and interaction modes after the core economics are proven.

| | |
|---|---|
| Workstream | G. Post-pilot |
| Duration | Ongoing, milestone-driven |
| Depends on | Phase 17 metrics show where expansion creates measurable value |
| Exit gate | Each expansion has its own business case, capacity plan and acceptance gate |
| Backlog | P18-01, P18-02, P18-03, P18-04, P18-05 |
| Decide first | D-12 a business case for each of teacher / school / app / AI |

## Objective

Grow deliberately into additional classes, teacher licensing, interactive learning, AI assistance and mobile applications, without destabilising the validated core.

> Scale should deepen the proven learning-commerce loop, not replace it with unrelated product categories.

## How to use this phase

This is a menu, not a sequence. Each item below becomes its own small phase with its own file in `docs/plan/`, written when the item is approved. Use the same layout as Phases 1–15.

Before any item starts, write down four things: the problem in numbers, the target segment, the success metric, and the point at which you stop or roll back.

## Potential deliverables

- [ ] Class 4 / 5 and early-years expansion
- [ ] Teacher single-classroom licence and dashboard
- [ ] School / B2B accounts, if demand exists
- [ ] Interactive exercises and auto-grading where suitable
- [ ] Personalised learning paths
- [ ] AI-assisted parent guidance with safeguards
- [ ] Mobile application / API, if usage warrants it
- [ ] Advanced search and recommendation infrastructure

## Steps

- [ ] **18.1** Add Class 4, then Class 5, only when content production capacity can keep quality and the one-month buffer. In code this is new rows in `classes` and the mapping tables; the `active` flags from Phase 3 let it be prepared privately.

- [ ] **18.2** Add early years only with age-appropriate design and a parent-led interaction model.

- [ ] **18.3** Create a teacher account and organisation model separately from household licensing. Do not overload parent permissions. This is a new domain (`Organisations`), not new columns on `users`.

- [ ] **18.4** For classroom licensing, define the seat or classroom scope, printable rights and redistribution restrictions clearly. Prices are in [../reference/commercial-config.md](../reference/commercial-config.md).

- [ ] **18.5** Add interactive questions only for content where browser interaction really improves learning. Keep the printable option where it is useful.

- [ ] **18.6** Introduce auto-grading only for objectively gradable questions.

- [ ] **18.7** If AI parent guidance is introduced, restrict it to reviewed curriculum and resources, and avoid claims of diagnosis or guaranteed outcomes. Log its answers and provide a route to a human.

- [ ] **18.8** Create a versioned API using Sanctum only when a mobile app or third-party client actually exists. This is when `routes/api.php` and API Resources first appear.

- [ ] **18.9** Consider a native app or mobile wrapper only after analytics show meaningful repeat mobile use and clear app-only benefits.

- [ ] **18.10** Revisit the architecture split only when scaling evidence shows a specific bottleneck. The modular monolith remains the default.

## Admin and operations

- New business roles and organisation controls are designed per expansion. They are not squeezed into the parent account.

## Required testing

- [ ] Every expansion gets a threat model, an authorization matrix, content QA and a regression suite before release.

## Exit checklist

- [ ] Each scale feature has a quantified problem, a target segment, a success metric and a rollback / stop criterion.

## Risks and controls

| Risk | Control |
|---|---|
| Feature sprawl | Require a business case and a phase gate |
| Teacher / parent permission collision | A separate organisation / teacher domain |
| AI safety and quality | Reviewed sources, a constrained scope, logging and human escalation |
| Premature architecture split | Measure the bottleneck before introducing services |

## Not in this phase

- Any scale feature without evidence from customers or operations
