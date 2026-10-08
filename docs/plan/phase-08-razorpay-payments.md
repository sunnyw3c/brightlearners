# Phase 8 — Razorpay payments, webhooks & refunds

Connect the order state machine to a resilient payment lifecycle.

| | |
|---|---|
| Workstream | C. Commerce |
| Duration | 1–2 weeks |
| Depends on | Phase 7 pending-payment orders are stable. A Razorpay test account, test keys and a webhook secret are available. |
| Exit gate | Payment success, failure and refund paths are verified, idempotent, and recover from a browser interruption |
| Backlog | P8-01, P8-02, P8-03 |
| Decide first | D-06 Razorpay account ownership, settlement details and refund permissions. D-14 refund policy. M-03 payment-method picker. |

## Objective

Implement Razorpay with server-created orders, signature verification, webhook reconciliation and safe retry / idempotency handling.

## Before you start

- The Razorpay PHP SDK is not covered by Boost `search-docs`. Use the Razorpay documentation for exact payloads, and re-check it when this phase starts.
- Mockup screen: "Secure Payment". It shows its own list of payment methods. Razorpay Checkout already lets the customer choose UPI, card or net banking, so the page needs one "Pay ₹…" button that opens Razorpay (M-03).
- A webhook needs a public URL. For local work, expose the site through a tunnel, or rely on the signed-payload tests below.

## Package and configuration

```bash
composer require razorpay/razorpay:"^2.9"
```

`.env` and `.env.example` (empty values in the example):

```
RAZORPAY_KEY_ID=
RAZORPAY_KEY_SECRET=
RAZORPAY_WEBHOOK_SECRET=
```

Read them through `config/services.php` (`services.razorpay.*`). Only the key ID ever reaches the browser, as a page prop. None of the three gets a `VITE_` prefix.

## Build steps

- [x] **8.1** Create the internal order first, then create the Razorpay Order from its authoritative total.
  - `App\Domains\Payments\Gateways\RazorpayGateway` implements the `PaymentGateway` contract from Phase 7.
  - `CreateRazorpayOrder` sends the amount in paise, currency `INR` and the internal order number as the receipt.

- [x] **8.2** Store the gateway order ID against the internal order, in a `payments` row with status `created`. If the pending order already has a Razorpay order, reuse it.

- [x] **8.3** Open Razorpay Checkout with the public key only. The secret never leaves the server. Load Razorpay's checkout script only on the payment page, and only in the browser (not during SSR).

- [x] **8.4** Verify the payment signature on the server when the browser calls back.
  - `VerifyRazorpayCallback` checks the signature with the SDK's utility, then fetches the payment from Razorpay and confirms it is captured and that amount and currency equal the internal order.
  - Only then does it call `MarkOrderPaid`.

- [x] **8.5** Receive webhooks and verify the HMAC signature using the raw request body and the webhook secret.
  - Use `$request->getContent()`. Do not re-encode parsed JSON.
  - Exclude `webhooks/*` from CSRF protection in `bootstrap/app.php` (`preventRequestForgery(except: [...])` in this Laravel version; confirm with `search-docs`).
  - An invalid signature returns 400 and is recorded with `signature_valid = false`.

- [x] **8.6** Save the `webhook_events` row before any side-effect, and make processing idempotent.
  - The event ID from Razorpay's header is stored with a unique index on `(provider, event_id)`. A second delivery fails the insert and is answered with 200 straight away.
  - The controller only stores the event and dispatches `ProcessRazorpayWebhook` on the `payments` queue.

- [x] **8.7** Treat webhook or provider verification as the final reconciliation, even when the browser callback never arrives. The callback and the webhook both end in the same `MarkOrderPaid` action, in either order.

- [x] **8.8** Record payment method, status and provider payload safely.
  - The stored payload is encrypted.
  - Secrets, signatures and full payloads are never written to the log.

- [x] **8.9** Protect against duplicate events, and wrap mark-paid and the entitlement trigger in a transaction.

  `MarkOrderPaid`:
  1. opens a transaction and locks the order row;
  2. returns quietly if the order is already `paid`;
  3. refuses if amount or currency differs from the order, and flags it for review;
  4. moves the order to `paid` through `TransitionOrder`, updates the payment to `captured`, sets `paid_at`;
  5. dispatches `OrderPaid` after the transaction commits.

- [x] **8.10** Implement the full and partial refund record model, if the business supports partial refunds (D-14), and keep it in step with the gateway.
  - A Filament action on the payment, guarded by `refunds.issue`. The amount cannot exceed what remains.
  - The `refunds` row starts as `pending`. The refund webhook moves it to `processed` and fires `RefundCompleted`.
  - The order becomes `refunded` or `partially_refunded`.

- [x] **8.11** Create the reconciliation command for pending orders with an unclear browser outcome.
  - `payments:reconcile`, scheduled every 10 minutes.
  - For each pending order older than a few minutes that has a Razorpay order, ask Razorpay for its payments and call `MarkOrderPaid` or mark it failed.
  - `orders:expire-pending` from Phase 7 runs after this and skips anything reconciliation is still handling.

- [x] **8.12** Build the customer-facing success, pending and failed states. Never show success before the server has confirmed it.
  - The success page reads the order status from the server. While the order is still pending it shows "confirming your payment" and re-checks every few seconds.
  - A failed payment offers a retry on the same order.

## Data model

Column detail is in [../reference/database-blueprint.md](../reference/database-blueprint.md#payments--phase-8).

| Table | Purpose |
|---|---|
| `payments` | Gateway order and payment IDs, verified status |
| `refunds` | Refund lifecycle |
| `webhook_events` | Provider events and the idempotency guard |

## Routes and screens

| Route | Purpose |
|---|---|
| `POST /checkout/payment` | Create the gateway order and return the checkout payload |
| `POST /payments/razorpay/callback` | Verify the browser payment result |
| `POST /webhooks/razorpay` | Raw webhook receiver. No session, no CSRF. |
| `/order/{order}/success` | Order result, owner only |
| `/order/{order}/payment-pending` | Reconciliation / pending state |

## Admin (Filament)

- Finance and admins can view payments and issue the refunds they are permitted to.
- Support can see payment status. The refund permission is separate (D-15).
- Webhook and reconciliation failures are visible to the technical admin: a list of `webhook_events` with status `failed`, and a list of orders pending longer than expected.

## Events, jobs and schedule

| Trigger | Result |
|---|---|
| `PaymentVerified` | `OrderPaid` |
| `OrderPaid` | Entitlement grant (Phase 9) and a queued `OrderConfirmation` email (`php artisan make:notification OrderConfirmation`) |
| `RefundCompleted` | Access reversal according to policy (Phase 9) |
| Schedule, every 10 minutes | `payments:reconcile` |

Every listener on `OrderPaid` must be safe to run twice.

## Tests to write

All in `tests/Feature/Payments/`. Fake the Razorpay HTTP calls; sign test webhooks with the test secret.

| Required test | Case |
|---|---|
| Successful payment | Valid callback → order `paid`, payment `captured`, `OrderPaid` fired once |
| Failed payment | Failure webhook → order `failed`, retry is possible |
| Browser closed after payment, webhook later marks the order paid | No callback; webhook alone completes the order |
| Webhook arrives before the browser callback | Webhook completes it; the later callback changes nothing |
| The same webhook delivered twice | Second delivery returns 200 and does nothing |
| Invalid callback signature rejected | Order stays pending |
| Invalid webhook signature rejected | 400, event stored as invalid, order unchanged |
| Amount mismatch never marks the order paid | Order stays pending and is flagged |
| Gateway timeout leaves a recoverable pending state | Order stays pending; `payments:reconcile` later resolves it |
| Full refund, and partial refund if supported | Status and amounts are correct; cannot refund more than was paid |
| A double-click on checkout does not create duplicate fulfilled orders | Two quick requests → one order, one Razorpay order, one `OrderPaid` |

## Exit checklist

- [x] No payment path can create duplicate entitlements or complete an order twice.
- [x] A paid order can be recovered when the browser callback never returns.
- [x] Support can tell failed, pending and paid apart.
- [x] The Razorpay secrets are absent from `public/build` and from the logs.

## Risks and controls

| Risk | Control |
|---|---|
| Duplicate webhooks | Unique event key, idempotent handler, transactional fulfilment |
| False success in the frontend | Server verification and webhook reconciliation |
| Secret leakage | Secrets stay on the server; logs are redacted |
| Payment ambiguity | Reconciliation job and a pending state in the UI |

## Not in this phase

- Recurring subscription mandates (post-pilot, Phase 17)
- Alternative gateways
