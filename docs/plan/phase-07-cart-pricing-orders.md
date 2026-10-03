# Phase 7 — Cart, pricing, coupons & order state machine

Create a trustworthy, server-authoritative checkout foundation before connecting Razorpay.

| | |
|---|---|
| Workstream | C. Commerce |
| Duration | 1–2 weeks |
| Depends on | Product catalogue and prices (Phase 6) |
| Exit gate | Cart totals and orders are deterministic, auditable and tested without a real payment gateway |
| Backlog | P7-01, P7-02, P7-03, P7-04 |
| Decide first | D-05 GST / tax / invoice treatment. D-16 whether a coupon stacks with the member discount. |

## Objective

Implement cart, discounts and order creation so the payment integration has a stable source of truth, and nothing done in the browser can change the amount payable.

> Payment gateway integration consumes an already-finalised server order. Razorpay is not the place to calculate your catalogue price.

## Before you start

- All amounts are integer paise. No `float` anywhere in this phase.
- Mockup screen: "Your Cart". Leave out the quantity steppers (M-05).
- The browser sends product IDs and a coupon code. It never sends a price, a discount or a total.

## Commands

```bash
php artisan make:model App/Domains/Commerce/Models/Cart --no-interaction
php artisan make:model App/Domains/Commerce/Models/CartItem --no-interaction
php artisan make:model App/Domains/Commerce/Models/Coupon --no-interaction
php artisan make:model App/Domains/Commerce/Models/CouponUsage --no-interaction
php artisan make:model App/Domains/Commerce/Models/Order --no-interaction
php artisan make:model App/Domains/Commerce/Models/OrderItem --no-interaction
php artisan make:migration create_commerce_tables --no-interaction
php artisan make:enum App/Domains/Commerce/Enums/OrderStatus --string --no-interaction
php artisan make:class App/Domains/Commerce/Services/PricingService --no-interaction
php artisan make:class App/Domains/Commerce/Actions/CreateCartOrder --no-interaction
php artisan make:class App/Domains/Commerce/Actions/ApplyCoupon --no-interaction
php artisan make:class App/Domains/Commerce/Actions/TransitionOrder --no-interaction
php artisan make:interface App/Domains/Payments/Contracts/PaymentGateway --no-interaction
```

Add `config/commerce.php` for order expiry minutes, tax settings and the order-number prefix.

## Build steps

- [ ] **7.1** Create carts and cart items. Keep a guest cart through a secure identifier and merge it on login.
  - A guest cart is found by `carts.token`, a random UUID in an encrypted, HTTP-only cookie.
  - `App\Domains\Commerce\Services\CartManager` returns the current cart for a guest or a user.
  - A listener on the `Login` event merges the guest cart into the user's cart. The same product is never added twice.
  - The cart can be used without an account. Checkout requires login, because a purchase needs a library to land in.

- [ ] **7.2** Reload product availability and price on the server before checkout, every time. A product that is no longer `active` is removed from the cart with a message.

- [ ] **7.3** Implement the pricing service. It applies the public sale price, the member discount and an approved coupon in a fixed order:

  1. Line price = `ProductPrice::for($product)` (sale price if its window is open, otherwise regular).
  2. Member discount on lines whose product is eligible, if the buyer has an active membership. Until Phase 10 this reads a `MembershipChecker` contract that always answers "no".
  3. Coupon on what remains, if D-16 allows stacking.
  4. Tax, from config. Zero until D-05 is decided.
  5. Total. It can never go below zero.

  `PricingService` returns a `PriceBreakdown` object: lines, subtotal, discount, tax, total. The cart page, the checkout page and `CreateCartOrder` all use this one object.

- [ ] **7.4** Represent money consistently. Percent discounts use integer arithmetic and one rounding rule (round half up). A discount is spread across lines so that the line totals add up exactly to the order total.

- [ ] **7.5** Create coupons with code, type, value, minimum order, schedule, maximum uses and a per-user limit.
  - `ApplyCoupon` checks active, date window, minimum order, total uses and uses by this user.
  - The usage limit is checked again inside the order transaction with the coupon row locked (`lockForUpdate`), so two simultaneous checkouts cannot both take the last use.
  - Do not allow a coupon that makes the total zero during the pilot. Razorpay cannot take a zero payment.

- [ ] **7.6** Create the order from the validated cart, and snapshot product name, SKU and type, unit price, discount, tax and final total on each order item.
  - `CreateCartOrder` runs in one database transaction: re-price, re-validate, lock the coupon, write the order and items, write the coupon usage, set `pending_payment`.
  - If the user already has an unexpired pending order for the same cart contents, return that order instead of creating another. This is what makes a double click safe.
  - Order number format: prefix + year + padded sequence, e.g. `BL-2026-000123`.

- [ ] **7.7** Define the states: `draft`, `pending_payment`, `paid`, `failed`, `cancelled`, `refunded`, `partially_refunded`.

  | From | Allowed next |
  |---|---|
  | `draft` | `pending_payment`, `cancelled` |
  | `pending_payment` | `paid`, `failed`, `cancelled` |
  | `failed` | `pending_payment` (retry), `cancelled` |
  | `paid` | `refunded`, `partially_refunded` |
  | `partially_refunded` | `refunded` |
  | `cancelled`, `refunded` | none |

  `TransitionOrder` is the only code that changes `orders.status`. It throws on a move that is not in the table.

- [ ] **7.8** Make sure a later product price change does not alter a historical order. Order pages read only `orders` and `order_items`.

- [ ] **7.9** Reserve the invoice and tax fields: `tax` on orders and items, `billing_state`, `gstin`, `invoice_number`. Do not finalise GST behaviour until the accountant confirms classification and invoice requirements (D-05).

- [ ] **7.10** Create the checkout screen with minimal fields and clear digital-delivery, licence and refund terms.
  - Fields: billing name, email (pre-filled), optional phone.
  - A required checkbox accepts the licence and refund terms, with links.
  - Every amount shown comes from `PriceBreakdown`.

- [ ] **7.11** Create the cleanup for abandoned pending orders.
  - Command `orders:expire-pending`, scheduled every 5 minutes.
  - A pending order past `expires_at` becomes `cancelled`, its coupon usage is released, and `OrderExpired` fires.
  - From Phase 8 onward, an order with a payment still being reconciled is skipped.

**Payment gateway contract.** Define `PaymentGateway` now with a `FakeGateway` implementation. Tests and this phase's exit gate use the fake. Phase 8 adds `RazorpayGateway` behind the same contract.

## Data model

Column detail is in [../reference/database-blueprint.md](../reference/database-blueprint.md#commerce--phase-7).

| Table | Purpose |
|---|---|
| `carts` | Guest or logged-in basket |
| `cart_items` | Products selected |
| `coupons` | Discount definitions |
| `coupon_usages` | Usage enforcement and audit |
| `orders` | The commercial transaction snapshot |
| `order_items` | Immutable purchased-line snapshots |

## Routes and screens

| Route | Purpose | Inertia page |
|---|---|---|
| `/cart` | View and edit the cart | `cart/show` |
| `/cart/items` | Add or remove a product | — |
| `/cart/coupon` | Apply or remove a coupon | — |
| `/checkout` | Validated checkout (login required) | `checkout/show` |
| `/account/orders` | Order history | `account/orders/index` |
| `/account/orders/{order}` | Order detail, owner only (`OrderPolicy`) | `account/orders/show` |

## Admin (Filament)

Navigation group: Commerce. In the mockup this is the "Orders" menu item.

- Order list and status view. Orders are read-only apart from audited actions.
- Coupon management needs `coupons.manage`.
- Any manual change to an order or a price is restricted and calls `Audit::record()`.

## Events, jobs and schedule

| Event | Fired when |
|---|---|
| `CartConvertedToOrder` | `CreateCartOrder` succeeds |
| `OrderExpired` | The scheduled cleanup cancels a pending order |

## Tests to write

| Required test | File and cases |
|---|---|
| Tampering with the price in the browser does not change the server total | `tests/Feature/Commerce/PriceTamperingTest.php` — extra `price` / `total` fields in the request are ignored |
| An expired or inactive product cannot be purchased | `tests/Feature/Commerce/UnavailableProductTest.php` — archived, draft, and deactivated-while-in-cart |
| Coupon limits hold under concurrent attempts | `tests/Feature/Commerce/CouponLimitTest.php` — with one use left, a second order is refused; per-user limit; released on expiry. Runs on MySQL so the row lock is real. |
| A guest cart merges without duplicate items | `tests/Feature/Commerce/CartMergeTest.php` |
| An order snapshot is unchanged after a product edit | `tests/Feature/Commerce/OrderSnapshotTest.php` — rename and re-price the product, the order is identical |
| An unauthorised user cannot view another order | `tests/Feature/Commerce/OrderAuthorizationTest.php` |
| Every discount combination | `tests/Unit/Commerce/PricingServiceTest.php` — sale only, coupon only, member only, each pair, all three, minimum order, rounding, line totals summing to the order total |
| Illegal state changes are refused | `tests/Unit/Commerce/OrderStateMachineTest.php` — one case per row of the table |

## Exit checklist

- [ ] A complete checkout reaches `pending_payment` with correct totals using the fake payment adapter.
- [ ] Every amount shown in checkout equals the order total produced by the pricing service.

## Risks and controls

| Risk | Control |
|---|---|
| Money calculation errors | One pricing service and a test for every discount combination |
| Coupon abuse | Limits, authorization, and a transaction with row locking |
| Tax ambiguity | A configurable tax layer and professional sign-off before launch |

## Not in this phase

- Gift cards
- A store-credit wallet
- Subscription proration
