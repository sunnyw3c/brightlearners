# BrightLearners implementation plan

- The phase-by-phase plan for this application is in `docs/`. Start at `docs/README.md`.
- Before implementing any feature, read the phase file in `docs/plan/` that covers it, and `docs/reference/architecture.md`.
- Before writing a migration, read `docs/reference/database-blueprint.md`. Before adding a route or page, read `docs/reference/routes-and-screens.md`.
- If a business rule is not written in `docs/`, add a question to `docs/tracking/decisions.md` and ask the user. Do not invent the behaviour.
- When a step is finished, tick it in its phase file and update `docs/tracking/backlog.md`.

## Rules that must stay true

- One Laravel application with domain folders under `app/Domains`. No microservices.
- A resource is learning content. A product is a commercial package that points at resources. Never copy a PDF into a product.
- Every access decision goes through `App\Domains\Access\Services\AccessService`. Controllers do not repeat purchase or membership logic.
- Paid PDFs and answer keys are on a private disk and are delivered only as short-lived temporary URLs.
- Price, discount, tax, totals and payment state are calculated and verified on the server. Money is stored as integer paise.
- A published resource version is immutable. A correction creates a new version.
- Children do not have accounts. A learning profile holds nickname, class and optional avatar or interests only.
- Email, file processing, indexing, aggregation and webhook side-effects run on the queue.
- Public pages are server-rendered through Inertia SSR.
- The model for the `classes` table is `SchoolClass`; the model for the `resources` table is `LearningResource`.
