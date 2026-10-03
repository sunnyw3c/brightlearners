# Staff roles and permissions

From Appendix D of the source plan, turned into permission names for `spatie/laravel-permission`. This is the planning baseline. It is refined in [Phase 2](../plan/phase-02-identity-security-roles.md) and finalised in [Phase 14](../plan/phase-14-admin-operations-support.md).

Permissions are enforced on the server, in policies and actions. Hiding a button is not enforcement.

## Roles

| Role | Slug | Who |
|---|---|---|
| Super Admin | `super-admin` | Technical owner. Passes every check. Sees system screens (webhooks, queues). |
| Business Admin | `business-admin` | Business owner. Prices, plans, staff, everything commercial. |
| Content Manager | `content-manager` | Prepares resources, products copy, programmes, articles. |
| Teacher Reviewer | `teacher-reviewer` | Reviews educational quality and answers. |
| Customer Support | `customer-support` | Helps parents with orders and access. |
| Finance | `finance` | Payments, refunds, reports. |
| Marketing | `marketing` | Articles and SEO. |

Parents have no role.

## Matrix

✓ full · View read-only · Review can review and comment · — none

| Capability | Super / Business Admin | Content Manager | Teacher Reviewer | Support | Finance | Marketing |
|---|---|---|---|---|---|---|
| Curriculum edit | ✓ | ✓ | Review | View | — | — |
| Resource draft / edit | ✓ | ✓ | Review | View | — | — |
| Publish resource | ✓ | Conditional | Approve gate | — | — | — |
| Product copy | ✓ | ✓ | View | View | — | View |
| Product price | ✓ | — | — | View | View | — |
| Orders / payments | ✓ | View | — | ✓ | ✓ | — |
| Refund | ✓ | — | — | Limited | ✓ | — |
| Entitlement override | ✓ | — | — | Limited + reason | — | — |
| Membership programme | ✓ | ✓ | Review | View | — | View |
| Customers | ✓ | — | — | ✓ limited | ✓ limited | — |
| Articles / SEO | ✓ | ✓ | — | — | — | ✓ |
| Roles / permissions | ✓ | — | — | — | — | — |
| Audit log | ✓ | View own area | — | Limited | Limited | — |

What the special values mean:

- **Conditional** — a Content Manager holds `resources.publish`, but `PublishResourceVersion` still refuses unless every required review is approved.
- **Approve gate** — a Teacher Reviewer cannot publish, but nothing publishes without their approval.
- **Limited refund** — to be defined in decision D-15. Suggested: Support can start a refund request that Finance approves.
- **Limited + reason** — Support can grant or extend access for a single resource with a written reason and an expiry. Support cannot grant a whole product or a membership.
- **✓ limited (customers)** — sees account, orders and access; learning profiles show nickname and class only.

## Permission names

Format: `area.action`.

| Permission | Super Admin | Business Admin | Content Manager | Teacher Reviewer | Support | Finance | Marketing |
|---|---|---|---|---|---|---|---|
| `curriculum.view` | ✓ | ✓ | ✓ | ✓ | ✓ | | |
| `curriculum.edit` | ✓ | ✓ | ✓ | | | | |
| `curriculum.review` | ✓ | ✓ | ✓ | ✓ | | | |
| `resources.view` | ✓ | ✓ | ✓ | ✓ | ✓ | | |
| `resources.edit` | ✓ | ✓ | ✓ | | | | |
| `resources.review` | ✓ | ✓ | | ✓ | | | |
| `resources.approve` | ✓ | ✓ | | ✓ | | | |
| `resources.publish` | ✓ | ✓ | ✓ | | | | |
| `products.view` | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| `products.edit-copy` | ✓ | ✓ | ✓ | | | | |
| `products.edit-price` | ✓ | ✓ | | | | | |
| `coupons.manage` | ✓ | ✓ | | | | | |
| `orders.view` | ✓ | ✓ | ✓ | | ✓ | ✓ | |
| `orders.manage` | ✓ | ✓ | | | ✓ | ✓ | |
| `refunds.issue` | ✓ | ✓ | | | | ✓ | |
| `refunds.request` | ✓ | ✓ | | | ✓ | ✓ | |
| `entitlements.override` | ✓ | ✓ | | | | | |
| `entitlements.override-limited` | ✓ | ✓ | | | ✓ | | |
| `programmes.view` | ✓ | ✓ | ✓ | ✓ | ✓ | | ✓ |
| `programmes.edit` | ✓ | ✓ | ✓ | | | | |
| `programmes.review` | ✓ | ✓ | | ✓ | | | |
| `plans.edit-price` | ✓ | ✓ | | | | | |
| `customers.view` | ✓ | ✓ | | | | | |
| `customers.view-limited` | ✓ | ✓ | | | ✓ | ✓ | |
| `articles.edit` | ✓ | ✓ | ✓ | | | | ✓ |
| `redirects.manage` | ✓ | ✓ | | | | | ✓ |
| `roles.manage` | ✓ | ✓ | | | | | |
| `audit.view` | ✓ | ✓ | | | | | |
| `audit.view-own-area` | ✓ | ✓ | ✓ | | | | |
| `audit.view-limited` | ✓ | ✓ | | | ✓ | ✓ | |
| `analytics.view` | ✓ | ✓ | | | | ✓ | ✓ |
| `analytics.export` | ✓ | ✓ | | | | | |
| `system.view` | ✓ | | | | | | |

`coupons.manage`, `plans.edit-price`, `redirects.manage`, `analytics.*`, `refunds.request` and `system.view` are not in the source matrix. They come from the admin controls described inside Phases 7, 8, 10, 12 and 13.

## Rules the tests must prove

| Rule | Phase |
|---|---|
| A parent cannot open `/admin` | 2 |
| Content Manager cannot issue refunds | 2, 8 |
| Support cannot publish resources | 2, 4 |
| Teacher Reviewer cannot change a price | 4, 14 |
| Content Manager cannot change a price | 6 |
| Support cannot assign a role | 14 |
| Only `super-admin` and `business-admin` manage staff | 2 |
