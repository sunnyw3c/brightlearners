# Phase 4 — Resource engine, versioning & review workflow

Create the educational-content system before the store.

| | |
|---|---|
| Workstream | B. Content platform |
| Duration | ~2 weeks |
| Depends on | Phase 3 taxonomy stable enough for tagging. A storage strategy has been chosen. |
| Exit gate | A reviewed resource can move from draft to a published version, with previews and audit history |
| Backlog | P4-01, P4-02, P4-03, P4-04, P4-05 |
| Decide first | D-02 object storage provider, bucket and CDN (still open — only blocks the staging/production bucket; local disks are configured now). T-05 PDF preview tooling (decided, default in use). |

## Objective

Build learning content as reusable, versioned resources with human review gates and file handling, so the same resource can later be packaged into products or membership.

> The resource engine is the core intellectual-property system. Products and memberships point at resources. They never own duplicate PDFs.

## Before you start

- **Model name.** The table is `resources`, the model is `LearningResource`. `Resource` is a soft-reserved word in PHP and clashes with Filament's `Resource` class.
- **Preview tooling.** Nothing on this machine can turn a PDF page into an image today. Install poppler, which provides `pdftoppm` and `pdfinfo`:
  - Windows: `scoop install poppler`
  - Server: `apt install poppler-utils`

  PHP's GD extension is already installed and converts the PNG output to WebP. `spatie/pdf-to-image` is not used because it needs `ext-imagick`.
- **Storage.** Two disks, defined in `config/filesystems.php`:

  | Disk | Visibility | Holds | Local | Staging / production |
  |---|---|---|---|---|
  | `resources` | private | source PDFs, low-ink PDFs, answer keys | `storage/app/private/resources` | private bucket (D-02) |
  | `previews` | public | preview images, covers | `storage/app/public/previews` | public bucket behind a CDN |

  When the bucket exists: `composer require league/flysystem-aws-s3-v3:"^3.0"`. Check `search-docs` (topic: filesystem, temporary URLs) for the local-disk temporary URL setting.

## Enums

Create with `php artisan make:enum App/Domains/Content/Enums/<Name> --string --no-interaction`.

| Enum | Cases |
|---|---|
| `ResourceType` | `Worksheet`, `Activity`, `Game`, `Reading`, `ParentGuide`, `StarterCheck`, `Other` |
| `ResourceStatus` | `Draft`, `EducationalReview`, `AnswerVerification`, `DesignPrintReview`, `Approved`, `Scheduled`, `Published`, `Archived` |
| `ReviewType` | `Educational`, `AnswerVerification`, `DesignPrint` |
| `ReviewStatus` | `Pending`, `Approved`, `ChangesRequested` |
| `PreviewStatus` | `Pending`, `Ready`, `Failed` |
| `CorrectionSeverity` | `Minor`, `Material` |

## Build steps

- [x] **4.1** Create the resource types: worksheet, activity, game, reading, parent guide, starter check, and `other` for approved types added later. An answer key is a file attached to a version, not a resource of its own.

- [x] **4.2** Create the resource metadata: title, slug, description, learning objective, class and skill mapping, difficulty, estimated time, supplies, page count, language, answer-key flag, low-ink flag, licence type.
  - Class and skill come from `resource_skill`, validated with the `SkillBelongsToClass` rule from Phase 3. One mapping is marked primary; it decides the canonical URL.
  - Page count is read from the PDF with `pdfinfo` and can be corrected by hand.

- [x] **4.3** Implement `resource_versions`. A published PDF is never overwritten.
  - The first version is `1.0`. A correction is `1.1`. A rewrite is `2.0`.
  - Files are stored under a random name inside the `resources` disk: `{resource_id}/v{version}/{uuid}.pdf`.
  - Once `published_at` is set, the row and its files cannot change. The model refuses updates to the file columns, and no action ever deletes a published file.

- [x] **4.4** Store source and print files on the private `resources` disk. Public previews go on the `previews` disk.

- [x] **4.5** Generate preview images on the queue after upload.
  - `App\Domains\Content\Services\PdfPreviewGenerator` runs `pdftoppm` through Laravel's `Process` facade, converts each page to WebP and records width and height.
  - Preview only the first pages (config `content.preview_pages`, suggested 3). A paid resource is never previewed in full.
  - The job is `GenerateResourcePreviews` on the `media` queue. It sets `preview_status` to `ready` or `failed`.

- [x] **4.6** Implement the workflow: Draft → Educational Review → Answer Verification → Design / Print Review → Approved → Scheduled → Published → Archived.

  | From | To | Condition |
  |---|---|---|
  | Draft | Educational Review | Required metadata is complete and a file is uploaded |
  | Educational Review | Answer Verification | Educational review approved |
  | Answer Verification | Design / Print Review | Every answer solved independently and approved |
  | Design / Print Review | Approved | Print test and language check approved |
  | any review state | Draft | A reviewer requests changes |
  | Approved | Scheduled | A publish time is set |
  | Approved or Scheduled | Published | `PublishResourceVersion` succeeds |
  | Published | Archived | Removed from sale and listing; history stays |

  `App\Domains\Content\Actions\PublishResourceVersion` is the only way to publish. It refuses unless all of these hold:
  1. class, skill, learning objective, type and page count are set;
  2. the version has an approved review of each required type;
  3. the answer-verification reviewer is not the person who uploaded the file;
  4. `preview_status` is `ready` and at least one preview exists.

  It then sets `published_at`, moves `is_current` to this version inside one transaction, and fires `ResourcePublished`.

- [x] **4.7** Record the reviewer, review date, notes and correction reason on every review and every correction.

- [x] **4.8** Add the internal `ai_assisted` flag. It is visible to staff only. Content with the flag follows exactly the same review gates; nothing unreviewed is ever exposed.

- [x] **4.9** Create the correction workflow.
  - "Create correction" on a published resource: upload the new file, write the change notes, choose the severity, tick whether customers must be told.
  - This creates a new unpublished version. The live version stays live until the new one passes the same reviews and is published.
  - On publish, write a `resource_corrections` row. If the correction is material and a notice is required, fire `MaterialCorrectionPublished`.
  - Add `App\Domains\Content\Queries\AffectedCustomers`, which returns who received the old version. It returns nobody until Phase 9 adds `downloads` and `entitlements`.

- [ ] **4.10** Add version metadata to downloads where practical: the delivered filename includes the version (`addition-practice-v1.1.pdf`) and the library shows the version and review date. Stamping the version inside the PDF needs a PDF library and is not part of the MVP.
  - Deferred: there is no `downloads` table or library screen until Phase 9. Revisit there.

- [x] **4.11** Create the staff preview screen and the public preview payload.
  - Staff: a Filament view page showing metadata, every preview, the review trail and links that open the private files through temporary URLs.
  - Public: one data class (`App\Domains\Content\Data\ResourcePreviewPayload`) that exposes preview image URLs and metadata. It never exposes a file path. Phase 5 wires it into a route.

- [x] **4.12** Handle low-ink and colour variants without duplicating the resource. The low-ink file is `low_ink_path` on the same version row.

## Data model

Column detail is in [../reference/database-blueprint.md](../reference/database-blueprint.md#learning-content--phase-4).

| Table | Purpose |
|---|---|
| `resources` | The logical learning item and its metadata |
| `resource_skill` | Class and skill links |
| `resource_versions` | Immutable published file versions |
| `resource_reviews` | Teacher, answer and design / print review records |
| `resource_previews` | Generated preview images |
| `resource_corrections` | Correction history and notice requirement |

## Upload rules

- PDF only. Check the real MIME type, not the extension.
- Maximum size from config (suggested 50 MB).
- The original filename is kept for display only. It is never used as a storage path.

## Admin (Filament)

Navigation group: Content. In the mockup this is the admin "Resources" table (Title, Class, Subject, Type, Status) and the "Content Review" menu item.

- Resource list with filters by status, class, subject and reviewer.
- A review queue page, an overdue-review list (older than a configured number of days) and a correction queue.
- `teacher-reviewer` can approve the educational and answer checks, but cannot touch commercial pricing.
- `content-manager` prepares drafts and can schedule only after the required approvals exist.
- Every status change, upload and publish calls `Audit::record()`.

## Events, jobs and schedule

| Trigger | Result |
|---|---|
| `ResourceVersionUploaded` | `GenerateResourcePreviews` job: preview images and page count |
| `ResourcePublished` | Cache invalidation now; search indexing from Phase 5 |
| `MaterialCorrectionPublished` | Queue the affected-customer notice when the policy requires it (the notice itself is Phase 11) |
| Schedule, every 5 minutes | `resources:publish-scheduled` publishes resources whose time has come |

## Tests to write

| Required test | File and cases |
|---|---|
| Publishing is blocked if a mandatory review is missing | `tests/Feature/Content/PublishResourceVersionTest.php` — one case per missing review type; self-verified answers are refused |
| The old version stays retrievable internally after an update | `tests/Feature/Content/VersioningTest.php` — after 1.1 is published, 1.0's row and file still exist and 1.1 is current |
| A preview failure does not publish a broken resource | same file as publish tests — `preview_status = failed` blocks publishing |
| A private file URL is not permanent or public | `tests/Feature/Content/PrivateFileTest.php` — the file is not on a public disk; its URL is temporary |
| Unsupported file types are rejected | `tests/Feature/Content/UploadValidationTest.php` — a renamed non-PDF is refused |
| A resource cannot publish without class, skill and objective | publish tests — one case per missing field |
| A published version cannot be changed | `tests/Feature/Content/VersioningTest.php` — updating file columns on a published version throws |

Use `Storage::fake()` for both disks and `Process::fake()` for `pdftoppm`.

## Exit checklist

- [ ] At least 3 representative resources complete the full workflow in staging. Blocked on D-01/D-02 (no staging environment yet); the workflow is proven in the automated test suite (`tests/Feature/Content`) against local disks.
- [x] A reviewer can verify an answer key independently — enforced in `RecordResourceReview` and re-checked in `PublishResourceVersion`; covered by tests.
- [ ] Preview pages render on mobile and desktop. Needs poppler installed locally (`scoop install poppler`) and a manual look; the generation and failure paths are covered by tests with `Process::fake()`.
- [x] The version 1.0 → 1.1 correction path is demonstrated — `tests/Feature/Content/CorrectionWorkflowTest.php` and `VersioningTest.php`.

## Risks and controls

| Risk | Control |
|---|---|
| Educational error | Mandatory teacher and answer-review states before publish |
| Silent overwrite | Immutable published versions |
| File exposure | Private storage and signed access |
| Operational bottleneck | Review queue visibility and a one-month content buffer |

## Not in this phase

- Customer-facing worksheet generator
- Interactive browser exercises
- Automatic AI approval
