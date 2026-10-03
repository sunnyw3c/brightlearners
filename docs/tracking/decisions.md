# Decision register

The source plan asks for one shared register of unresolved business rules (Phase 0), so a developer never has to invent behaviour. This is that register.

How to use it:

- When a phase needs a rule that is not written down, add a row here and ask. Do not guess in code.
- When a decision is made, fill in **Decision**, **Decided by** and **Date**, and change **Status** to `Decided`.
- A phase must not start while a decision in its "Decide first" row is `Open`.

Status values: `Open` · `Default in use` (building on the recommended default until someone objects) · `Decided`.

## Business decisions

D-01 to D-12 are the gates from Appendix N of the source plan. D-13 to D-17 are further rules the plan leaves open inside its phases.

| ID | Needed before | Decision | Recommended default | Status | Decision | Decided by | Date |
|---|---|---|---|---|---|---|---|
| D-01 | Phase 1 staging | Hosting / deployment target and domain names | Choose between a Linux server (as the source plan describes) and Laravel Cloud (the repo has its deploy skill). Local work is not blocked. | Open | | | |
| D-02 | Phase 4 | Object storage provider, bucket and CDN strategy | An S3-compatible private bucket for files and a public bucket behind a CDN for previews | Open | | | |
| D-03 | Phase 5 | Do free downloads need a login or email, or stay frictionless with optional sign-up? | Frictionless, with an optional email box | Open | | | |
| D-04 | Phase 6 | Exact launch SKUs; which products are member-discount eligible; does the 15% member discount stay in the pilot? | Fill the table in [../reference/commercial-config.md](../reference/commercial-config.md) | Open | | | |
| D-05 | Phase 7 | GST / tax / invoice treatment, from a qualified adviser | Tax fields reserved and set to zero until confirmed | Open | | | |
| D-06 | Phase 8 | Razorpay account ownership, settlement details and refund permissions | — | Open | | | |
| D-07 | Phase 9 | Can an expired member re-download packs released during their membership? | No re-download after expiry; files already downloaded stay usable | Open | | | |
| D-08 | Phase 10 | Pilot start date and the exact class / programme release calendar | One fixed weekly release day and time for all classes | Open | | | |
| D-09 | Phase 12 | Final brand, domain and indexable URLs / slugs | "BrightLearners" is the working name used in the mockup and the repo | Open | | | |
| D-10 | Phase 16 | Legal / privacy / policy review complete; support owner assigned | — | Open | | | |
| D-11 | Phase 17 | Does pilot evidence support recurring billing and assessment investment? | Decide from the 90-day review | Open | | | |
| D-12 | Phase 18 | Business case for teacher / school / app / AI expansion | One written case per item | Open | | | |
| D-13 | Phase 2 | What may a parent do before verifying their email? | Browse and download free resources; verify before checkout, library and dashboard | Open | | | |
| D-14 | Phase 8, 9 | Refund policy: are partial refunds supported, and what does a refund do to access? | Full refunds only during the pilot; a full refund revokes that order's access | Open | | | |
| D-15 | Phase 2, 8, 14 | What does "Limited" refund mean for Customer Support? | Support can request a refund; Finance or an admin issues it | Open | | | |
| D-16 | Phase 7 | Does a coupon stack with the member discount? | Yes, in the order sale price → member discount → coupon; no coupon may bring the total to zero | Open | | | |
| D-17 | Phase 10 | What happens when a current member tries to buy membership again? | Blocked while a membership is active | Open | | | |

## Mockup against plan

The mockup and the source plan disagree on these points. Detail is in [../reference/design-reference.md](../reference/design-reference.md#where-the-mockup-and-the-plan-disagree).

| ID | Needed before | Question | Recommended default | Status | Decision | Decided by | Date |
|---|---|---|---|---|---|---|---|
| M-01 | Phase 3 | Is Hindi a launch subject? The mockup shows it; the plan's scope is Maths, English, EVS / mixed. | Not at launch | Open | | | |
| M-02 | Phase 5 | Show star ratings, review counts, a Reviews tab and Save / heart? The plan defers these to Phase 17. | Leave out. Never show invented ratings. | Open | | | |
| M-03 | Phase 8 | Build our own payment-method picker? | No. One Pay button opens Razorpay Checkout. | Open | | | |
| M-04 | Phase 6 | Show the "30-Day Support" badge? | Not until the support policy is approved | Open | | | |
| M-05 | Phase 6, 7 | Quantity steppers on product and cart? | No. Quantity is always 1 for a digital household product. | Open | | | |
| M-06 | Phase 6 | Use "Aligned to School Curriculum"? The plan rules out compliance claims. | Use wording the teacher reviewer approves | Open | | | |
| M-07 | Phase 14 | Custom dark admin as drawn? | A Filament theme with a dark sidebar | Open | | | |
| M-08 | Phase 11 | How is the dashboard progress figure worded? | "Activities opened / completed", never a score | Open | | | |

## Technical defaults

These are choices made while fitting the source plan to this repo. They are in use unless you change them here.

| ID | Choice | Why | Status |
|---|---|---|---|
| T-01 | Re-base the project onto the official React starter kit in Phase 1, not retrofit Inertia into the plain skeleton | The plan's step 1.1 asks for it, the repo has no custom code yet, and the kit brings auth and 2FA that Phase 2 needs | Default in use |
| T-02 | PHP 8.5 everywhere; Composer must run on the same PHP as the CLI | Today Composer runs on 8.3.31 and the CLI on 8.5.7, which makes package resolution wrong | Default in use |
| T-03 | The test suite runs on MySQL, not in-memory SQLite | Row locks, JSON and strict mode must behave as in production; coupon and payment tests depend on it | Default in use |
| T-04 | Redis in Docker for local work, with the `database` drivers as a fallback | No Redis server is installed on this machine | Default in use |
| T-05 | PDF previews use poppler (`pdftoppm`, `pdfinfo`) and PHP's GD | No Imagick or Ghostscript is installed, and `spatie/pdf-to-image` needs `ext-imagick` | Default in use |
| T-06 | Money is stored as integer paise and sent to React as `{ paise, formatted }` | No floating-point money; the same unit as Razorpay | Default in use |
| T-07 | Membership access is computed by `AccessService` from subscriptions and released weeks; it is not copied into `entitlements` rows | One rule stays correct when a profile changes class, a member joins mid-month or the re-download policy changes. The alternative needs backfill jobs for each of those. | Default in use |
| T-08 | A bundle is a product that points at other products (`bundle_products`) | The plan asks for one consistent representation; this one matches "three-product bundle" | Default in use |
| T-09 | The pilot is sold as a product of type `membership` through the normal cart, order and Razorpay flow | No second checkout to build or test | Default in use |
| T-10 | Class slugs are stored as `class-1`, with a route constraint | Gives the plan's `/class-1/maths` URLs without catching other top-level pages | Default in use |
| T-11 | `User` stays in `app/Models`. The models for `classes` and `resources` are `SchoolClass` and `LearningResource`. | Packages expect `App\Models\User`; `class` is reserved and `resource` is soft-reserved in PHP | Default in use |
| T-12 | Horizon runs only on the Linux staging and production servers; local uses `queue:work` | Horizon needs `ext-pcntl` and `ext-posix`, which Windows lacks | Default in use |
| T-13 | `audit_logs` is created in Phase 3, not Phase 14 | Phases 3, 7 and 9 already require logged changes | Default in use |
| T-14 | `AccessService` and `/download/{resource}` first appear in Phase 5 with the free-resource rule | Free downloads then use the same single access path that paid downloads use later | Default in use |
| T-15 | Filament classes live in `app/Filament`, not inside the domain folders | Default Filament discovery works, and admin stays a thin layer over domain actions | Default in use |

## Tables added beyond the source plan

| Table or column | Why | Phase |
|---|---|---|
| `class_topic` | Per-class copy for topic landing pages (step 5.3) | 5 |
| `products.primary_class_id` | The `{class}` part of the product URL | 6 |
| `products.subscription_plan_id` | Links the membership product to its plan | 10 |
| `resource_versions.preview_status` | Blocks publishing when preview generation failed | 4 |
| `resources.is_free`, `resources.featured` | The explicit free flag (step 9.4) and homepage featuring | 4, 5 |
| `downloads.access_source`, `downloads.variant` | Correction notices and reporting | 9 |
| `learning_weeks.released_at` | Makes the release job safe to run twice | 10 |
| `analytics_events.dedupe_key` | Makes server events fire once | 13 |
| `article_relations` | Links articles to classes, skills, resources, products (step 12.2) | 12 |
| `feedback` (optional) | Pilot feedback tagging | 16 |
