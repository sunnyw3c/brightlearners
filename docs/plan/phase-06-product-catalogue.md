# Phase 6 — Product catalogue & merchandising

Package resources into sellable products without duplicating learning content.

| | |
|---|---|
| Workstream | C. Commerce |
| Duration | 1–2 weeks |
| Depends on | Resources exist and can be previewed (Phase 4). The Phase 0 launch product list is approved. |
| Exit gate | Topic packs, workbooks, ebooks and bundles can be published with real previews and related offers |
| Backlog | P6-01, P6-02, P6-03, P6-04 |
| Decide first | D-04 exact launch SKUs and member-discount eligibility. M-04, M-05, M-06 (mockup wording and quantity). |

## Objective

Create the commercial catalogue and the product ladder, while keeping resource ownership separate from product pricing and merchandising.

> A resource may appear in free samples, products, bundles and membership. The product points at it. It never copies it.

## Before you start

- Prices and the launch SKU list are in [../reference/commercial-config.md](../reference/commercial-config.md). All prices are stored in paise.
- Mockup screen: "Maths Mastery Workbook (Class 2)". Three things in it need a decision before you copy them:
  - the quantity stepper (M-05) — a digital product is bought once, so leave it out;
  - the "30-Day Support" badge (M-04) — a policy promise;
  - "Aligned to School Curriculum" (M-06) — Phase 3 rules out compliance claims.
- The Reviews tab is left out (M-02).

## Commands

```bash
php artisan make:model App/Domains/Catalog/Models/Product --no-interaction
php artisan make:migration create_products_tables --no-interaction
php artisan make:factory ProductFactory --no-interaction
php artisan make:enum App/Domains/Catalog/Enums/ProductType --string --no-interaction
php artisan make:enum App/Domains/Catalog/Enums/ProductStatus --string --no-interaction
php artisan make:policy App/Domains/Catalog/Policies/ProductPolicy --no-interaction
```

## Build steps

- [ ] **6.1** Create the product types: `topic_pack`, `workbook`, `activity_ebook`, `holiday_pack`, `revision_pack`, `bundle`, plus any approved launch type. Phase 10 adds `membership`.

- [ ] **6.2** Map one or more resources to a product with a sort order (`product_resources`).

- [ ] **6.3** Create bundle composition without copying files. A bundle is a product of type `bundle` whose `bundle_products` rows point at other products.
  - Add `Product::deliverableResources()`: for a normal product it returns its resources; for a bundle it returns the resources of every child product, without duplicates. Phase 9 uses this to grant access.
  - A bundle cannot contain another bundle.

- [ ] **6.4** Create `regular_price` and an optional `sale_price` with start and end dates. All money is integer paise.
  - Add `App\Domains\Catalog\Services\ProductPrice::for($product, $at)`: the sale price when the sale window is open, otherwise the regular price. Phase 7's pricing service builds on it.
  - A sale price must be lower than the regular price.

- [ ] **6.5** Add product status `draft` / `active` / `archived` and a publish schedule (`publish_at`, command `products:publish-scheduled`).

  A product can become `active` only when:
  - a normal product has at least one resource with a published current version;
  - a bundle has at least two active child products;
  - a membership product (Phase 10) has a plan — its deliverable is not a file.

- [ ] **6.6** Create the cover image and real preview pages from the included resources. Reuse `resource_previews`; do not upload separate sample images. A product with only a cover and no real sample page cannot be published.

- [ ] **6.7** Show on the product page: inclusions, class, subject, skills, total pages, answer-key information, language, print format, licence and delivery method. Total pages is the sum of the included resources' page counts.

- [ ] **6.8** Create related-product logic by class, subject and next-step skill:
  1. bundles that contain this product (the upsell);
  2. other active products for the same class and subject;
  3. products for the next skill in the topic's order.

- [ ] **6.9** Implement the `member_discount_eligible` flag separately from the public price. It is read only by pricing. It never gives access to anything.

- [ ] **6.10** Handle the flagship ebooks.
  - *Maths Through Games* is listed only after its content and answers pass QA (Phase 4 workflow).
  - *1000 Essential English Words* stays in a separate English-learning collection unless it is adapted. It has no primary class, so its URL uses `all-classes` in the class segment.

- [ ] **6.11** Build the shop browse filters: class, subject, type and price band. Reuse the filter components and the `noindex` rule from Phase 5. Add `Searchable` to `Product` so products appear in `/search`.

## Data model

Column detail is in [../reference/database-blueprint.md](../reference/database-blueprint.md#catalogue--phase-6).

| Table | Purpose |
|---|---|
| `products` | The sellable offer: price, merchandising, SEO |
| `product_resources` | Which resources a product includes |
| `bundle_products` | Which products a bundle includes |
| `product_prices` | Optional, not built. The MVP keeps the price on the product and snapshots it on the order item. |

`products.primary_class_id` is an addition. It provides the `{class}` segment of the product URL and the class filter.

## Routes and screens

| Route | Purpose | Inertia page |
|---|---|---|
| `/shop` | Shop root with filters | `shop/index` |
| `/shop/{type}` | Filtered commercial landing. `{type}` is one of `topic-packs`, `workbooks`, `ebooks`, `holiday-packs`, `revision-packs`, `bundles`. | `shop/type` |
| `/shop/{class}/{slug}` | Product detail, canonical | `shop/show` |
| `/products/{slug}` | Optional alias. 301 to the canonical URL. | — |

"Add to Cart" and "Buy Now" are wired up in Phase 7. "Buy Now" means add to cart and go to checkout.

## Admin (Filament)

Navigation group: Commerce. In the mockup this is the "Workbooks" menu item.

- Only a role with `products.edit-price` changes a live price. The check is in the policy and the form request, not just the form.
- `content-manager` can edit copy and previews but not prices.
- An archived product still resolves for past orders and the library. It cannot be bought.
- Price changes and status changes call `Audit::record()`.

## Tests to write

| Required test | File and cases |
|---|---|
| A product cannot publish without a deliverable | `tests/Feature/Catalog/ProductPublishTest.php` — no resources; resources with no published version; bundle with one child |
| An order snapshot cannot depend on the mutable product name or price | Written in Phase 7 (`OrderSnapshotTest`). Here: `ProductPriceTest` covers sale windows and boundaries. |
| The member-discount flag does not grant access | Written in Phase 9 (`AccessServiceTest`). Here: assert the flag is only a boolean column with no relation to access. |
| A bundle includes the correct resources | `tests/Feature/Catalog/BundleCompositionTest.php` — `deliverableResources()` returns the union without duplicates; nested bundles are refused |
| Price permission is enforced on the server | `tests/Feature/Catalog/ProductPricePermissionTest.php` — `content-manager` cannot change a price even with a crafted request |
| An archived product cannot be bought but still resolves | `tests/Feature/Catalog/ArchivedProductTest.php` |

## Exit checklist

- [ ] Every planned launch product exists in staging with a representative preview and full metadata.
- [ ] A parent can tell exactly what is included before checkout.
- [ ] Prices can be changed in admin without a deployment.

## Risks and controls

| Risk | Control |
|---|---|
| Confusing catalogue | Use the product ladder and class / skill filters. Avoid dozens of near-duplicate SKUs. |
| Hard-coded price | All commercial rules are stored or configured, and snapshotted on orders |
| Weak preview | Require real sample pages, not a cover-only listing |

## Not in this phase

- Marketplace seller accounts
- A complex dynamic pricing engine
- Multi-currency launch
