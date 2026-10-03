# Analytics event dictionary and pilot scorecard

From Appendix F of the source plan. Implemented in [Phase 13](../plan/phase-13-analytics-kpis.md).

## Rules

- An event name is permanent. It is never renamed or reused for a different meaning.
- "Server" events are written by server code and are the source of truth. "Client" events are sent by the browser and are indicative only.
- Server events that follow a payment carry a `dedupe_key`, so retries do not double-count.
- Money properties are in paise.
- Events never contain a child's nickname, a parent's email or free text typed by a user, apart from the search query.

## Event dictionary

| Event | Authority | Core properties | Fired from | Phase |
|---|---|---|---|---|
| `resource_view` | Client / server | `resource_id`, `class`, `subject`, `skill`, `source` | Resource detail page | 13 |
| `resource_preview` | Client | `resource_id`, `preview_page` | Preview carousel | 13 |
| `free_download` | Server | `resource_id`, `version_id`, user or anonymous ID | `ResourceDownloaded` with source `free` | 13 |
| `product_view` | Client / server | `product_id`, `class`, `price` | Product detail page | 13 |
| `add_to_cart` | Server | `product_id`, `price`, `cart_id` | Cart add | 13 |
| `checkout_started` | Server | `order_id`, `total`, `item_count` | `CartConvertedToOrder` | 13 |
| `purchase_completed` | Server | `order_id`, `revenue`, `items`. Idempotent: `dedupe_key` = order ID. | `OrderPaid` | 13 |
| `payment_failed` | Server | `order_id`, reason category | Payment failure | 13 |
| `membership_view` | Client | plan context | `/membership` | 13 |
| `membership_started` | Server | `subscription_id`, `plan_id`, paid amount | `MembershipActivated` | 13 |
| `resource_opened` | Server / client | `profile_id`, `resource_id`, `source` | `ResourceOpened` | 13 |
| `resource_downloaded` | Server | `profile_id`, `resource_id`, `version_id` | `ResourceDownloaded` | 13 |
| `resource_completed` | Server | `profile_id`, `resource_id` | `ResourceCompleted` | 13 |
| `search_performed` | Server / client | `query`, `filters`, `result_count` | `/search` | 13 |
| `assessment_started` | Server | `assessment_id`, `profile_id` | Assessment start | 17 |
| `assessment_completed` | Server | `assessment_id`, `profile_id`, skill bands | Assessment finish | 17 |
| `recommendation_clicked` | Server | `recommendation_id`, `target` | Recommendation link | 17 |

## Funnel

visitor → free resource → product → purchase → membership → weekly use

| Step | Metric key | Definition |
|---|---|---|
| Visitor | `visitors` | Distinct anonymous IDs with any event that day |
| Free resource | `free_downloaders` | Distinct IDs with `free_download` |
| Product | `product_viewers` | Distinct IDs with `product_view` |
| Purchase | `purchasers` | Distinct users with a paid order |
| Membership | `members_started` | Subscriptions activated |
| Weekly use | `weekly_active_members` | Active members with `resource_opened`, `resource_downloaded` or `resource_completed` on a membership resource in the week |

## KPIs

The source plan names these KPIs but does not define them. The definitions below are proposals. Confirm or change each one when Phase 13 builds it; after that a definition must not change silently.

| KPI | Metric key | Definition | Source |
|---|---|---|---|
| Average order value | `aov` | Captured revenue ÷ paid orders | `orders`, `payments` |
| Paid conversion | `paid_conversion` | Purchasers ÷ visitors | events + `orders` |
| Active members | `active_members` | Subscriptions with status `active` at end of day | `subscriptions` |
| Member activation | `member_activation` | Members who opened at least one pack within 7 days of joining ÷ new members | `subscriptions`, `resource_progress` |
| Repeat weekly use | `repeat_weekly_use` | Members active in two or more consecutive weeks ÷ active members | `resource_progress` |
| Refund rate | `refund_rate` | Refunded orders ÷ paid orders | `refunds`, `orders` |
| Support rate | `support_rate` | Support contacts ÷ active families | support log |
| Renewal intent | `renewal_intent` | Share of pilot families saying they would continue | feedback |

Revenue is always computed from `orders` and `payments`, never from events.

## Pilot scorecard

Fill in the threshold column in Phase 0 (step 0.8). The pilot is judged against these numbers.

| Question | Metric family | What it tells you | Threshold |
|---|---|---|---|
| Can we attract the right parent? | Organic / social visits → relevant free-resource view or download | Acquisition quality, not just traffic | |
| Will they pay? | Product conversion, first-order AOV, pilot membership sales | Demand and willingness to pay | |
| Do they use it? | Weekly pack opens / downloads / completions | Activation and repeat use | |
| Is the content good enough? | Confusion reports, correction rate, teacher QA failures | Quality and rework burden | |
| Can we support it? | Tickets and support minutes per family, payment and download failure rate | Operational scalability | |
| Will they continue? | Renewal intent, regular-membership conversion after the pilot | Retention signal | |
| Can we produce it? | Next-month readiness, review cycle time, pages / resources completed | Supply-side reliability | |
| Does it contribute? | Revenue − fees − variable review / support / marketing allowances | Economic direction. Track founder time separately. | |
