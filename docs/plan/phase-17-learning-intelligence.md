# Phase 17 — Post-pilot learning intelligence

Add assessments, recommendations and recurring plans only after the pilot proves repeat demand.

| | |
|---|---|
| Workstream | G. Post-pilot |
| Duration | 4–8 weeks |
| Depends on | The pilot review approves continued investment. Enough resources exist per skill to make recommendations useful. |
| Exit gate | Skill checks can recommend next resources. Regular membership can renew using validated billing and cancellation rules. |
| Backlog | P17-01, P17-02, P17-03, P17-04 |
| Decide first | D-11 whether pilot evidence supports recurring billing and assessment investment. M-02 reviews and favourites. |

## Objective

Use the validated taxonomy and behaviour data to make the experience more personal, without jumping straight to opaque AI.

> This phase should be funded by evidence. If the pilot shows families mostly buy one-time products and ignore the weekly membership, prioritise the catalogue and SEO, not subscription complexity.

## Before you start

- Do not start from this document alone. Re-read the 90-day review from Phase 16 and re-plan this phase against it.
- The Phase 3 taxonomy is what makes this phase possible: questions map to skills, scores are per skill, recommendations are per skill.
- Completion (Phase 11) and assessment results are different data. Keep them in different tables and word them differently.

## Deliverables

- [ ] Starter assessment engine
- [ ] Skill scores
- [ ] Rule-based recommendations
- [ ] Improved progress dashboard
- [ ] Recurring Razorpay subscription integration, if approved
- [ ] Favourites / recently used / reviews, if evidence supports them
- [ ] Class 4 preparation (optional)

## Build steps

- [ ] **17.1** Create assessments by class and subject, with sections and questions mapped to skills and difficulty. Assessments are versioned and go through the same teacher review idea as resources.

- [ ] **17.2** Create attempts, answers and results, and score by skill. State clearly that this is an informal learning check, not formal certification.
  - Scoring is a pure function of the answers and the assessment version, so it can be re-run.
  - A result is shown as a simple band per skill, never a precise-looking number.

- [ ] **17.3** Start recommendations with deterministic rules: class + weak skill + an available reviewed resource. Never recommend another class's content or an unpublished product.

- [ ] **17.4** Show why a recommendation exists, in parent-friendly language. Each recommendation stores a `reason_code` that maps to a sentence.

- [ ] **17.5** Track recommendation click, purchase and use. Add `recommendation_clicked` and the assessment events to the analytics dictionary.

- [ ] **17.6** Add parent feedback labels such as easy / comfortable / needs practice, only if their meaning is clear.

- [ ] **17.7** If regular membership is approved, activate the monthly, quarterly and annual plans and implement the recurring subscription lifecycle: mandate / authorization, renewal success and failure, cancellation, the grace and access policy, webhook reconciliation and customer communication.
  - Reuse the Phase 8 webhook pipeline and its idempotency guard.
  - Add provider subscription IDs to `subscriptions`.
  - Re-check Razorpay's subscription documentation and current rules for recurring payments in India when this step starts.

- [ ] **17.8** Use the pilot's actual willingness-to-pay and support data to re-test the membership price. Do not lock in the planning assumptions.

- [ ] **17.9** Add Meilisearch only if catalogue size or search behaviour shows a need for typo tolerance, facets or scale.

## Data model

Column detail is in [../reference/database-blueprint.md](../reference/database-blueprint.md#assessment-tables--phase-17).

| Table | Purpose |
|---|---|
| `assessments` | Versioned learning checks by class and subject |
| `assessment_sections` | Sections in order |
| `assessment_questions` | Skill-linked questions with difficulty and answer config |
| `assessment_attempts` | One attempt by one learning profile |
| `assessment_answers` | Responses and correctness |
| `skill_scores` | Derived band per profile and skill |
| `recommendations` | Rule-based next-resource suggestions and whether they were acted on |
| recurring provider IDs | New columns on `subscriptions` and `payments` |

## Routes and screens

| Route | Purpose |
|---|---|
| `/assessment/{slug}` | Starter or skill check |
| `/account/assessments` | History and results |
| `/account/recommendations` | Suggested next learning |
| `/membership/manage` | Recurring plan: manage and cancel, once activated |

## Admin (Filament)

- The teacher and content team manage assessment versions and reviews.
- A business admin can activate or deactivate recurring plans only after the support and cancellation workflow is approved.

## Tests to write

| Required test | Case |
|---|---|
| Scoring is deterministic and versioned | The same answers always give the same result; an old attempt is scored against its own assessment version |
| A recommendation never suggests the wrong class or an unpublished product | One case each |
| An assessment result cannot be reached from another profile | Another user's attempt returns 403 |
| Recurring renewal webhook idempotency | The same renewal event twice extends the term once |
| Cancellation and access end-date rules match the customer terms | Time travel through cancel, grace and end |

## Exit checklist

- [ ] An assessment result maps to skills a parent can understand.
- [ ] Recommendations have measurable click and use signals.
- [ ] A recurring plan can complete success, failure, cancel and expiry in test mode.

## Risks and controls

| Risk | Control |
|---|---|
| False precision | Simple bands and clear limits. Never presented as a diagnosis. |
| AI overreach | Start deterministic and explainable |
| Subscription support burden | Activate recurring billing only after cancellation and failure processes are ready |

## Not in this phase

- An AI tutor
- Automatic diagnosis of learning disorders
- A black-box adaptive curriculum
