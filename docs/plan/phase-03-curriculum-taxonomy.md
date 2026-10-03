# Phase 3 — Curriculum taxonomy & core domain model

Model class → subject → topic → skill so content, SEO and recommendations share one language.

| | |
|---|---|
| Workstream | A. Product foundation |
| Duration | 1–2 weeks |
| Depends on | Phase 2 learning profiles. The teacher or content lead has approved the first class / subject / topic / skill map (Phase 0). |
| Exit gate | The curriculum taxonomy is seeded, editable under governance and ready to be referenced by resources |
| Backlog | P3-01, P3-02, P3-03 |
| Decide first | M-01 whether Hindi is a launch subject |

## Objective

Create the curriculum structure that powers filtering, URLs, resource metadata, membership planning, assessment mapping and later recommendations.

> This phase is foundational. If the taxonomy is weak, SEO, search, assessments, membership sequencing and analytics all become harder later.

## Before you start

- You need the approved curriculum map from Phase 0 in the row format shown in [phase-00-scope-freeze.md](phase-00-scope-freeze.md#curriculum-map-format).
- `class` is a reserved word in PHP. The model is `SchoolClass`, the table is `classes`, the foreign key is `class_id`.

## Commands

```bash
php artisan make:model App/Domains/Curriculum/Models/SchoolClass --no-interaction
php artisan make:model App/Domains/Curriculum/Models/Subject --no-interaction
php artisan make:model App/Domains/Curriculum/Models/Topic --no-interaction
php artisan make:model App/Domains/Curriculum/Models/Skill --no-interaction
php artisan make:migration create_curriculum_tables --no-interaction
php artisan make:migration create_audit_logs_table --no-interaction
php artisan make:factory SchoolClassFactory --no-interaction     # and Subject, Topic, Skill
php artisan make:seeder CurriculumSeeder --no-interaction
php artisan make:rule SkillBelongsToClass --no-interaction
```

## Build steps

- [ ] **3.1** Create classes, subjects, topics and skills with stable identifiers and slugs.
  - A slug is set once, when the row is created. Editing the name does not change it.
  - Changing a slug is a separate, deliberate admin action. Once pages are public (Phase 5) it also writes a row to `redirects`.

- [ ] **3.2** Represent the hierarchy explicitly: `subjects` → `topics` → `skills`, with `class_subject` and `class_skill` saying what applies to each class. Do not encode the curriculum in product categories or tags.

- [ ] **3.3** Seed Class 1, Class 2 and Class 3 with slugs `class-1`, `class-2`, `class-3`.

- [ ] **3.4** Seed Maths, English and the practical / EVS / mixed categories exactly as approved in Phase 0. Seed Hindi only if M-01 says so.

- [ ] **3.5** Seed topic and skill rows from the approved curriculum map.
  - Keep the map as one data file, `database/seeders/data/curriculum.php`, so the teacher-approved content is reviewable in one place.
  - `CurriculumSeeder` uses `updateOrCreate` on slugs. It is safe to run again.

- [ ] **3.6** Allow many-to-many mappings only where they are needed.
  - One skill row can serve several classes through `class_skill`, each with its own objective wording and difficulty.
  - Do not create a second skill with the same name for another class or product.

- [ ] **3.7** Add `sort_order` to every table. It drives curriculum display and the SEO hierarchy.

- [ ] **3.8** Add `active` flags so future curriculum (Class 4, new topics) can be prepared without being shown.
  - Each model gets an `active()` query scope.
  - Public queries always go through the scopes. Add one query class, `App\Domains\Curriculum\Queries\CurriculumTree`, that returns class → subjects → topics → skills using active rows and active mappings only.

- [ ] **3.9** Create the admin screens, with validation that prevents accidental deletion of referenced curriculum.
  - Filament resources for class, subject, topic and skill, with relation managers for the class mappings.
  - There is no delete button for a row that has content. The action is "Archive" and it sets `active = false`.
  - Policies return false for `delete` when the row is referenced.

- [ ] **3.10** Define the naming conventions used in navigation, URLs and metadata.

  | Thing | Rule | Example |
  |---|---|---|
  | Class name | "Class" + number | Class 2 |
  | Class slug | `class-` + number | `class-2` |
  | Subject name | Short everyday name | Maths, English, EVS |
  | Topic name | Title Case, "and" not "&" | Addition and Subtraction |
  | Slug | lower-case, hyphens, no IDs | `addition-and-subtraction` |
  | Skill name | Starts with a verb | Add two 2-digit numbers |

**Rule for later phases.** A resource can be tagged with `(class, skill)` only when that pair exists and is active in `class_skill`. Write `SkillBelongsToClass` now. Phase 4 uses it on the resource form.

**Audit log.** The source plan says curriculum changes are logged, but lists the audit table under Phase 14. Create `audit_logs` and a small helper, `App\Support\Audit::record($action, $subject, $before, $after, $metadata)`, here. Call it when curriculum rows are created, edited or archived. Phase 14 adds the viewer.

**Learning profiles.** Add the foreign key from `learning_profiles.class_id` to `classes`, and switch the profile form from the fixed list to the real active classes.

## Data model

Column detail is in [../reference/database-blueprint.md](../reference/database-blueprint.md#curriculum--phase-3).

| Table | Purpose |
|---|---|
| `classes` | Class 1–3 and future classes |
| `subjects` | Maths, English, EVS and so on |
| `topics` | A subdivision of a subject |
| `skills` | One learning objective |
| `class_subject` | Which subjects a class offers, and in what order |
| `class_skill` | Which skills belong to a class, with class-specific objective and difficulty |
| `audit_logs` | Created here, completed in Phase 14 |

## Routes and screens

| Route | Purpose | This phase |
|---|---|---|
| `/learn` | Learning browse root | A plain page listing classes and their subjects from `CurriculumTree`. Styled in Phase 5. |
| `/class-{slug}` | Class landing | Phase 5 |
| `/class-{slug}/{subject}` | Subject landing | Phase 5 |
| `/class-{slug}/{subject}/{topic}` | Topic landing | Phase 5 |

## Admin (Filament)

- Navigation group: Content → Curriculum.
- Curriculum changes are logged.
- Deleting a class, topic or skill that has content is blocked. Use inactive / archive.
- `teacher-reviewer` holds `curriculum.review`: it can read and comment on curriculum wording, but cannot change products or prices.

## Tests to write

| Required test | File and cases |
|---|---|
| Slug uniqueness | `tests/Feature/Curriculum/SlugTest.php` — a duplicate class or subject slug is rejected; a topic slug is unique within its subject; renaming does not change the slug |
| Hierarchy queries return only active mappings | `tests/Feature/Curriculum/CurriculumTreeTest.php` — an inactive subject, topic, skill or mapping is absent from the tree |
| A Class 2 resource cannot be tagged to a Class 1-only skill without an explicit mapping | `tests/Unit/Curriculum/SkillBelongsToClassTest.php` — the rule fails without a `class_skill` row and passes with one |
| Admin cannot hard-delete referenced taxonomy | `tests/Feature/Curriculum/TaxonomyDeletionTest.php` — the policy denies delete for a referenced row; archive succeeds |
| Seeder is repeatable | `tests/Feature/Curriculum/CurriculumSeederTest.php` — running it twice gives the same row counts |

## Exit checklist

- [ ] Every launch resource can be assigned to a class, subject, topic and skill.
- [ ] Navigation and search filters can be built from the taxonomy, not from hard-coded arrays.
- [ ] The teacher lead approves the seeded taxonomy.

## Risks and controls

| Risk | Control |
|---|---|
| Taxonomy churn | Stable IDs and slugs; archive, never delete |
| Too much granularity | Create only the skill depth needed for browsing, assessment and recommendations |
| The same worksheet reused carelessly across classes | Class-specific mappings and objectives |

## Not in this phase

- Adaptive curriculum graph
- Standards-compliance or certification claims
- Publishing a full Class 4 / 5 taxonomy
