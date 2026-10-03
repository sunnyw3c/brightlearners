# Phase 5 — Public learning library, SSR & search

Turn resources into a fast, indexable discovery experience.

| | |
|---|---|
| Workstream | B. Content platform |
| Duration | ~2 weeks |
| Depends on | Phase 4 can publish resources. Inertia SSR works (Phase 1). |
| Exit gate | Class, subject, topic and resource pages are server-rendered, filterable, searchable and usable on mobile |
| Backlog | P5-01, P5-02, P5-03, P5-04 |
| Decide first | D-03 whether free downloads need a login or email. M-02 ratings and Save button. |

## Objective

Build the free-learning and discovery layer that attracts parents, explains the learning intent, and routes them towards the right paid product or membership.

> The free library is the acquisition engine. Its usability and SEO quality matter as much as the paid store.

## Before you start

- This is the first phase with real public design. Use [../reference/design-reference.md](../reference/design-reference.md) and the mockup screens "Home", "Class 2 Learning Resources" and "Addition Practice Worksheet".
- Load the `tailwindcss-development` skill before writing markup.
- Leave out star ratings, review counts and the Save / heart button unless M-02 says otherwise. Never show invented ratings.

## Packages

```bash
composer require laravel/scout:"^11.8"
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider" --no-interaction
```

`.env`: `SCOUT_DRIVER=database`. Meilisearch is not installed.

## Build steps

- [ ] **5.1** Build the homepage hero around the parent's problem and class selection, not a company biography.
  - Sections from the mockup: hero with two calls to action (Explore Free Resources → `/free`, Browse Workbooks → `/shop`), four category cards, Browse by Class.
  - Class cards come from the database.
  - "Browse Workbooks" and the membership card link to pages that arrive in Phases 6 and 10. Hide or disable them until then.

- [ ] **5.2** Build `/learn` and the class pages so they expose the curriculum structure. Use `CurriculumTree` from Phase 3.

- [ ] **5.3** Build subject and topic pages with useful explanatory copy, related skills and resources — never an empty grid.
  - Copy comes from `classes.intro`, `class_subject.intro` and the new `class_topic.intro`.
  - A landing page with no published resource and no copy is `noindex` and left out of navigation.

- [ ] **5.4** Build `/free` and the resource detail page with class, subject, skill, objective, time, pages, answer key, print mode, supplies and preview.
  - The canonical URL uses the primary class and subject: `/free/class-2/maths/addition-practice-worksheet`.
  - The preview carousel uses `resource_previews` with width and height set, so the page does not jump.

- [ ] **5.5** Let free resources download with minimal friction, following D-03.
  - Recommended: no login. An optional "email me this worksheet" box may be offered. It must not be a gate.
  - Downloads go through `GET /download/{resource}` and `App\Domains\Access\Services\AccessService`. In this phase the service knows two rules: the resource is published, and it is free. Phase 9 adds entitlements to the same service.
  - The response is a redirect to a short-lived temporary URL. The page never contains a storage URL.
  - Fire `ResourceDownloaded` with the resource, version and user or anonymous ID. Nothing listens yet; Phase 9 logs it and Phase 13 counts it.

- [ ] **5.6** Create related-resource logic from class and skill tags: same class and skill first, then same class and topic, then same class and subject. Exclude the current resource. Limit to 4–6.

- [ ] **5.7** Implement breadcrumb navigation: Home → Class → Subject → Topic → Resource. Build the trail on the server and pass it as a prop. Phase 12 reuses it for structured data.

- [ ] **5.8** Implement search with the Scout database driver.
  - Add `Searchable` to `LearningResource`. Index title, summary, description and objective. Only published resources are searchable.
  - Return results through one `SearchResult` data class (type, title, summary, URL, class, free or paid) so products and articles can join in Phases 6 and 12 without changing the page.

- [ ] **5.9** Add filters for class, subject, topic, type, free / paid and difficulty.
  - Filters are query-string parameters, validated in a Form Request.
  - Filter options come from the taxonomy tables.

- [ ] **5.10** Use stable, human-readable URLs. No database ID appears in a canonical content URL. Constrain the class parameter as described in [../reference/routes-and-screens.md](../reference/routes-and-screens.md#url-rules).

- [ ] **5.11** Make sure the server-rendered HTML contains the title and the content.
  - Set title, description and canonical with Inertia's `<Head>`.
  - Check each page type with `curl` while SSR is running.

- [ ] **5.12** Add empty states and no-results guidance: suggest the class page, the popular topics, or clearing filters.

## Data model

| Table | Purpose |
|---|---|
| `class_topic` | **Added.** Per-class landing copy for topic pages (step 5.3) |
| `redirects` | Optional here. Create it now if a published slug has to change before Phase 12. |
| download events | No table yet. The `ResourceDownloaded` event reserves the capability; Phase 9 stores it and Phase 13 formalises analytics. |

## Routes and screens

| Route | Purpose | Controller | Inertia page |
|---|---|---|---|
| `/` | Commercial homepage | `HomeController` | `home` |
| `/learn` | Browse learning | `Learn\LearnController` | `learn/index` |
| `/class-1` | Class landing | `Learn\ClassController` | `learn/class` |
| `/class-1/maths` | Subject landing | `Learn\SubjectController` | `learn/subject` |
| `/class-1/maths/addition` | Topic landing | `Learn\TopicController` | `learn/topic` |
| `/free` | Free resources root | `Free\FreeResourceController@index` | `free/index` |
| `/free/{class}/{subject}/{slug}` | Free resource detail | `Free\FreeResourceController@show` | `free/show` |
| `/search?q=` | Unified search | `SearchController` | `search/index` |
| `/download/{resource}` | Access check, then temporary URL | `DownloadController` | — |

Controllers stay thin. Each one calls a query class that eager-loads what the page needs.

## Shared components

Create these once in `resources/js/components` and reuse them in Phases 6, 9 and 11:

`SiteHeader`, `SiteFooter`, `PublicLayout`, `Breadcrumbs`, `ClassCard`, `SubjectChips`, `TopicCard`, `ResourceCard`, `PreviewCarousel`, `MetaList`, `EmptyState`, `Pagination`.

## Admin (Filament)

- The content manager can feature resources on the homepage and class pages (`resources.featured`).
- Landing copy and SEO copy can be edited without a deployment: class intro, class-subject intro, class-topic intro.

## Tests to write

| Required test | File and cases |
|---|---|
| The SSR response contains indexable content before hydration | `scripts/ssr-smoke` (or a CI step): with SSR running, `curl` one URL of each page type and check for the heading and body text |
| Canonical URLs are stable | `tests/Feature/Library/CanonicalUrlTest.php` — the canonical uses the primary class and subject; a non-canonical path redirects |
| Filters do not create uncontrolled indexable duplicates | `tests/Feature/Library/FilterIndexingTest.php` — a URL with filter parameters carries `noindex` and a canonical without them |
| Search returns the expected resource for exact and partial terms | `tests/Feature/Library/SearchTest.php` — exact title, partial word, and an unpublished resource that must not appear |
| Mobile previews remain readable | Manual check on a phone-width viewport; record the result in the exit checklist |
| A broken or unpublished resource returns the correct status or redirect | `tests/Feature/Library/ResourceVisibilityTest.php` — draft is 404; archived is 404 or redirects per the rule you set; unknown slug is 404 |
| A paid resource cannot be downloaded as free | `tests/Feature/Access/FreeDownloadTest.php` — free and published is allowed; anything else is denied |

## Exit checklist

- [ ] A parent can go from the homepage to a Class 2 skill and download a suitable free resource in 4 meaningful interactions or fewer.
- [ ] Every launch taxonomy landing page has real, non-placeholder content.
- [ ] No public page exposes the URL of a full paid PDF.
- [ ] Pages work at phone width.

## Risks and controls

| Risk | Control |
|---|---|
| Thin SEO pages | Require a useful description, learning intent and real resources before a page is indexable |
| Filter duplication | Canonical and `noindex` for arbitrary query combinations |
| Slow previews | Pre-generated images, CDN, lazy loading |

## Not in this phase

- Meilisearch, unless search quality or catalogue size requires it
- A personalised recommendation engine
