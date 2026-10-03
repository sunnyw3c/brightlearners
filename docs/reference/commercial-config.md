# Commercial configuration baseline

From Appendix K of the source plan. These are planning prices. Every price is a test price, is stored in the database in paise, and can be changed in admin without a deployment.

## Offers

| Offer | Planning price | Stored as (paise) | Product type | Note |
|---|---|---|---|---|
| Topic pack (12–20 learner pages) | ₹79 | 7900 | `topic_pack` | Server-managed test price |
| Workbook (30–60 pages) | ₹149–199 | 14900–19900 | `workbook` | Varies by depth and page count |
| Large activity ebook | ₹199–299 | 19900–29900 | `activity_ebook` | Flagship only after QA |
| Holiday pack | ₹249 | 24900 | `holiday_pack` | Seasonal |
| Three-product bundle | ₹349–449 | 34900–44900 | `bundle` | Bundle composition is explicit |
| Class / subject starter bundle | ₹499–699 | 49900–69900 | `bundle` | High-value entry package |
| 90-day pilot | ₹499 prepaid | 49900 | `membership` (plan `pilot_90`) | No assumed auto-renewal |
| Family monthly, later | ₹199 / month | 19900 | plan `monthly` | Re-test after the pilot. Recurring only when operations are ready. |
| Family quarterly, later | ₹549 / quarter | 54900 | plan `quarterly` | Re-test |
| Family annual, later | ₹1,799 / year | 179900 | plan `annual` | Accounting treatment needs professional advice |
| Teacher, later | ₹349 / month or ₹2,999 / year | 34900 / 299900 | — | Single-teacher classroom licence. Not in the MVP. |

## Product ladder

free → ₹79 topic pack → ₹149–199 workbook → ₹349–449 bundle → membership

## Member discount

- Planning assumption: 15% on eligible one-time products while the membership is active.
- Whether it applies in the pilot, and to which products, is decision D-04.
- Eligibility is the `member_discount_eligible` flag on a product. The percentage is on the plan.
- The flag affects price only. It never grants access.

## Launch catalogue (BR-05)

Fill this in during Phase 0 (step 0.4). Phase 6 creates these products and Phase 16 loads them.

| # | Product | Type | Class | Price (₹) | Member discount? | Content status |
|---|---|---|---|---|---|---|
| 1–4 | Topic packs, Class 1 | `topic_pack` | 1 | 79 | | |
| 5–8 | Topic packs, Class 2 | `topic_pack` | 2 | 79 | | |
| 9–12 | Topic packs, Class 3 | `topic_pack` | 3 | 79 | | |
| 13 | Flagship ebook 1 (*Maths Through Games*, after QA) | `activity_ebook` | | | | |
| 14 | Flagship ebook 2 | `activity_ebook` | | | | |
| 15–17 | Bundles | `bundle` | | | | |
| 18 | 90-day pilot membership | `membership` | all | 499 | n/a | |

Plus 15–20 free resources, 3 starter checks and one complete membership month per class.

*1000 Essential English Words* stays in a separate English-learning collection unless it is adapted for Classes 1–5.

## Licence rules

- A family purchase is for use within the purchasing household.
- A teacher licence, later, covers one teacher and one classroom, with exact print and use rules.
- Redistribution, resale and public upload are prohibited, stated in plain language.
- Licence, refund / cancellation, delivery and access rules are shown before payment.
- A purchased product stays governed by its purchase licence whatever happens to a membership.
- For membership, state what happens to packs already downloaded and whether old packs can be downloaded again after expiry (decision D-07).

## Tax

GST, invoicing and place-of-supply treatment are not decided (decision D-05). The order tables reserve the fields. Until the accountant confirms, tax is zero in configuration and no invoice numbers are issued.

## Where each rule lives in the code

| Rule | Location | Phase |
|---|---|---|
| Product price and sale window | `products` table, `ProductPrice` | 6 |
| Discount order, rounding | `PricingService` | 7 |
| Coupons | `coupons` table, `ApplyCoupon` | 7 |
| Order expiry, tax rate, order-number prefix | `config/commerce.php` | 7 |
| Plan price and length | `subscription_plans` | 10 |
| Member discount percent | `subscription_plans.benefits` | 10 |
| Re-download after expiry | `config/membership.php` | 9, 10 |
