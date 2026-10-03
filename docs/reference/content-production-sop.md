# Learning-content production procedure

From Appendix E of the source plan. This is the procedure the content team follows. The software that enforces it is built in [Phase 4](../plan/phase-04-resource-engine.md).

## Flow

```
Skill plan
   ↓
Draft resource (AI may assist)
   ↓
Qualified educational review
   ↓
Independent answer verification
   ↓
Design + Hindi / font rendering review where applicable
   ↓
Print test: A4 / low-ink / instructions / page order
   ↓
Approved resource version
   ↓
Scheduled publication
   ↓
Feedback + correction log
   ↓
New version when required
```

How the flow maps to the system:

| Step | Status in the system | Who |
|---|---|---|
| Skill plan, draft | `Draft` | Content Manager |
| Educational review | `EducationalReview` | Teacher Reviewer |
| Answer verification | `AnswerVerification` | A reviewer who did not write the answers |
| Design, language and print test | `DesignPrintReview` | Content Manager or designer |
| Approved | `Approved` | — |
| Scheduled publication | `Scheduled` → `Published` | Content Manager |
| Correction | New version 1.1, same reviews again | Content Manager + reviewers |

## Rules

- AI may help with drafting and illustration. Nothing AI-drafted is published without the same human reviews.
- A qualified teacher reviews learning objectives, difficulty and instructions.
- Every answer is solved independently by someone other than the author.
- Print and language checks are required.
- A published file is never replaced. A correction is a new version with a change note.
- A material correction triggers a notice to everyone who received the old version.

## Metadata required before approval

- [ ] Class and subject
- [ ] Topic and skill
- [ ] Learning objective
- [ ] Estimated activity time
- [ ] Supplies / materials
- [ ] Learner page count
- [ ] Answer-key inclusion
- [ ] Language
- [ ] Colour and / or low-ink availability
- [ ] Household / teacher licence classification
- [ ] Version number and review date
- [ ] Representative preview pages

## Monthly membership production rhythm

| Stage | Work |
|---|---|
| Planning | Confirm class skills, objective sequence, difficulty and month theme |
| Drafting | Create learner pages, activities, parent notes and first answers |
| Review | Educational review, independent solving, language and layout checks |
| QA | Print tests, file generation, versioning, previews, fixing findings |
| Publish prep | Schedule the weeks, create emails, verify access and the next-month buffer |
| Feedback | Collect parent confusion and error reports and feed them into the next version |

Production stays at least one month ahead of delivery. At any time, the month now being delivered and the month after it are both fully approved.

## Launch content target

| Item | Count |
|---|---|
| Topic packs | 12 (four each for Classes 1, 2, 3) |
| Flagship ebooks | 2, checked |
| Bundles | 3 |
| Free resources | 15–20 |
| Starter checks | 3 (one per class) |
| Membership months | 1 complete month per class, plus a buffer month |
