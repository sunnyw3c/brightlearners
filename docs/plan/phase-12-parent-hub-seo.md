# Phase 12 — Parent Hub, SEO system & discoverability

Build the organic acquisition engine around useful learning content and technically clean indexing.

| | |
|---|---|
| Workstream | E. Growth engine |
| Duration | ~2 weeks |
| Depends on | Public resource pages (Phase 5) and products (Phase 6) exist |
| Exit gate | The public site has canonical URLs, metadata, sitemaps, structured data, internal linking and useful parent content |
| Backlog | P12-01, P12-02, P12-03, P12-04, P12-05 |
| Decide first | D-09 final brand, domain and indexable URLs / slugs |

## Objective

Make SEO part of the product architecture, so free resources, class and topic pages and parent guides attract relevant search traffic and lead naturally into the product ladder.

## Before you start

- The full checklist is [../checklists/seo.md](../checklists/seo.md). Work through it at the end of this phase.
- `spatie/laravel-sitemap` 8.x needs PHP 8.4 or newer. Phase 1 put the project on 8.5.
- Laravel's skeleton ships a static `public/robots.txt`. Step 12.6 replaces it with a route, so delete the file.

## Packages

```bash
composer require spatie/laravel-sitemap:"^8.2"
```

No other package is needed. Structured data is plain JSON built on the server.

## Commands

```bash
php artisan make:model App/Domains/Growth/Models/Article --no-interaction
php artisan make:model App/Domains/Growth/Models/ArticleCategory --no-interaction
php artisan make:model App/Domains/Growth/Models/Redirect --no-interaction
php artisan make:migration create_growth_tables --no-interaction
php artisan make:class App/Domains/Growth/Data/SeoData --no-interaction
php artisan make:class App/Domains/Growth/Services/SeoBuilder --no-interaction
php artisan make:class App/Domains/Growth/Services/StructuredData --no-interaction
php artisan make:middleware HandleRedirects --no-interaction
php artisan make:middleware BlockIndexingOutsideProduction --no-interaction
```

## Build steps

- [ ] **12.1** Create the article categories: learning at home, maths help, English help, reading, activities, learning routines, parent guides. Seed them.

- [ ] **12.2** Build the article CMS with author, publish date and related class, skills, resources and products.
  - Filament resource with a rich-text body. Sanitise the stored HTML before it is rendered on the public site.
  - Relations go in `article_relations`.
  - Add `Searchable` to `Article` so guides appear in `/search`.

- [ ] **12.3** Create template-driven SEO titles and meta descriptions with a manual override. Editors should not have to fill every field.
  - `SeoBuilder` produces a `SeoData` object (title, description, canonical, robots, Open Graph) for each page type from a template, for example `{resource title} — Class {n} {subject} worksheet | BrightLearners`.
  - A model's own `seo_title` / `seo_description` wins when it is filled.
  - Controllers pass `seo` as a page prop. One React `<Seo>` component renders it inside Inertia's `<Head>`, so it is in the server-rendered HTML.

- [ ] **12.4** Define canonical URLs for classes, topics, resources, products and articles. One function per model returns its canonical URL; the sitemap, the `<Seo>` component and structured data all call it.

- [ ] **12.5** Mark arbitrary filter and sort combinations `noindex`, unless one is deliberately promoted to its own landing page. Phase 5 set this up; confirm it for the shop and Parent Hub.

- [ ] **12.6** Create segmented sitemaps for pages, resources, products and articles. Include only published, canonical URLs.
  - `/sitemap.xml` is an index pointing at `/sitemaps/pages.xml`, `/sitemaps/resources.xml`, `/sitemaps/products.xml`, `/sitemaps/articles.xml`.
  - Generate them with a scheduled command and cache the output.
  - `/robots.txt` is a route. In production it allows crawling and names the sitemap. Everywhere else it disallows everything.

- [ ] **12.7** Add `BreadcrumbList` structured data wherever a hierarchy exists. Reuse the breadcrumb trail built in Phase 5.

- [ ] **12.8** Add `Organization` (site-wide) and `Article` structured data.

- [ ] **12.9** Add `Product` / `Offer` structured data to sellable product pages, using the current server price and availability. Do not add rating data unless real reviews exist.

- [ ] **12.10** Add redirect management for changed slugs and retired pages.
  - A Filament resource for `redirects`.
  - `HandleRedirects` looks up the path only when the request would otherwise be a 404.
  - On save, refuse a redirect that points at itself, at another redirect's source, or back to its own source.
  - Changing a published slug writes the redirect automatically.

- [ ] **12.11** Create an SEO landing page only when it offers useful, unique explanatory content and real resources. No empty keyword shells.

- [ ] **12.12** Implement Open Graph image, title and description, with site-wide defaults for pages that have none.

- [ ] **12.13** Add Search Console verification and sitemap submission to the launch checklist. The verification value comes from config, not from the template.

- [ ] **12.14** Create internal links from articles to the relevant free resource, paid pack and membership, where they fit the context. Render the article's `article_relations` as a "Try this" block.

## Data model

Column detail is in [../reference/database-blueprint.md](../reference/database-blueprint.md#growth-and-operations--phase-12-and-13).

| Table | Purpose |
|---|---|
| `articles` | Parent Hub content |
| `article_categories` | Guide categories |
| `article_relations` | **Added.** Links from an article to classes, skills, resources, products |
| `redirects` | 301s and retired slugs |
| `seo_overrides` | Optional, not built. SEO fields stay on the content models. |

## Routes and screens

| Route | Purpose | Inertia page |
|---|---|---|
| `/parents` | Parent Hub root | `parents/index` |
| `/parents/{category}` | Guide category | `parents/category` |
| `/blog/{slug}` | Article detail | `articles/show` |
| `/sitemap.xml` | Sitemap index | — |
| `/robots.txt` | Robots policy | — |

## Admin (Filament)

Navigation group: Marketing.

- `marketing` can edit articles and SEO fields. It cannot change product access or payment logic.
- Redirect management is limited to roles with `redirects.manage`, to avoid loops.

## Staging and indexing

- `BlockIndexingOutsideProduction` adds `X-Robots-Tag: noindex, nofollow` to every response when the environment is not production.
- The production `robots.txt` is the only one that allows crawling.
- Write down how indexing is switched on at launch, in [../reference/deployment-runbook.md](../reference/deployment-runbook.md).

## Tests to write

| Required test | File and cases |
|---|---|
| The SSR source contains title, H1, body and JSON-LD | Extend the SSR smoke check from Phase 5 to one URL per page type, including a product and an article |
| The canonical points to the correct stable URL | `tests/Feature/Growth/CanonicalTest.php` — every page type; filtered URLs point at the unfiltered page |
| No unpublished URL appears in the sitemap | `tests/Feature/Growth/SitemapTest.php` — drafts, archived products and unpublished articles are absent |
| Structured data validates structurally | `tests/Feature/Growth/StructuredDataTest.php` — required keys per type; valid JSON; the price matches the server price |
| Redirect chains are avoided | `tests/Feature/Growth/RedirectTest.php` — self, loop and chain are refused on save; a valid redirect returns 301 |
| The 404 page returns a true 404 | `tests/Feature/Growth/NotFoundTest.php` — status 404, not 200 with an error page |
| Staging is not indexable | `tests/Feature/Growth/IndexingControlTest.php` — non-production responses carry the `noindex` header and a blocking `robots.txt` |

## Exit checklist

- [ ] Every launch resource and product is reachable through crawlable internal links, not only through site search.
- [ ] The sitemaps validate, and staging can be blocked from indexing.
- [ ] The production indexing controls are documented.
- [ ] [../checklists/seo.md](../checklists/seo.md) is complete.

## Risks and controls

| Risk | Control |
|---|---|
| Thin or duplicate pages | A quality threshold and the canonical / `noindex` policy |
| JavaScript-only indexing | SSR is required for public content |
| SEO drift | Central templates, and automated checks in the release checklist |

## Not in this phase

- Mass programmatic pages without useful content
- Paid backlink schemes
