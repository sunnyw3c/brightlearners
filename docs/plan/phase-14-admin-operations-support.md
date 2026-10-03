# Phase 14 — Admin operations, corrections & customer support

Make the platform operable by business staff without routine developer intervention.

| | |
|---|---|
| Workstream | E. Growth engine |
| Duration | ~1 week |
| Depends on | All major domain models exist |
| Exit gate | Content, products, orders, customers, corrections and membership operations are manageable from admin, with audit trails |
| Backlog | P14-01, P14-02, P14-03, P14-04 |
| Decide first | D-15 the limit on Support refunds. M-07 admin theme. |

## Objective

Finish the operating layer around Filament: dashboards, queues, support actions, correction notices and controlled manual overrides.

> The admin panel is part of the product. If every price, content or support change needs a developer, the business will not scale operationally.

## Before you start

- Most Filament resources already exist from Phases 2–13. This phase organises them, fills the gaps and checks every permission.
- `audit_logs` and `Audit::record()` exist since Phase 3. This phase adds the viewer and proves coverage.
- The mockup's admin has a dark sidebar. Apply that as a Filament theme (M-07). Do not build a custom admin.
- Use `search-docs` (package `filament/filament`) for navigation groups, widgets, custom pages, actions and exports.

## Build steps

- [ ] **14.1** Group the admin into Content, Commerce, Membership, Customers, Marketing, Support and System.

  | Group | Contains | Mockup item |
  |---|---|---|
  | Content | Curriculum, resources, review queue, corrections | Resources, Content Review |
  | Commerce | Products, bundles, coupons, orders, payments, refunds | Workbooks, Orders |
  | Membership | Plans, subscriptions, programmes, month-at-a-glance | Membership |
  | Customers | Parents, learning-profile summary | Users |
  | Marketing | Articles, categories, SEO fields, redirects | — |
  | Support | Customer view, access check, support notes | Support |
  | System | Staff, roles, audit log, webhook events, analytics, settings | Analytics, Settings |

- [ ] **14.2** Create dashboards for: content awaiting review, programmes missing items, failed payments, expiring memberships, correction notices. Each is a Filament widget that links to the filtered list.

- [ ] **14.3** Add audit logging for price changes, publishing, file replacement and new versions, refunds, entitlement grants and role changes.
  - Go through each of the six and confirm `Audit::record()` is called from the action, not from the form.
  - Add a read-only audit viewer, filtered by actor, subject and action.
  - `content-manager` sees its own area. Support and Finance see a limited view.

- [ ] **14.4** Create the support customer view: orders, payment state, membership, the reason for library access and notification history, with as little learner data as possible. One page per parent, built from existing queries. Learning profiles show nickname and class only.

- [ ] **14.5** Implement manual access grant and revoke with a mandatory reason and an optional expiry. Complete the action started in Phase 9 and make it available from the support customer view.

- [ ] **14.6** Create the correction-notice workflow that selects affected purchasers and members from resource version and download history.
  - From a published material correction: preview the recipient list from `AffectedCustomers`, confirm, send `CorrectionNotice`, set `resource_corrections.notified_at`.
  - Sending twice is blocked.

- [ ] **14.7** Add export functions for finance and order reporting as needed, with permission restrictions. CSV exports of orders, payments and refunds, queued, and recorded in the audit log.

- [ ] **14.8** Use soft-delete and archive patterns, never destructive delete, for business records. Check every Filament resource: no bulk hard delete on orders, payments, entitlements, resources, versions, products or users.

- [ ] **14.9** Document the operational procedures for publishing, refunds, corrections, failed payments and customer-access troubleshooting. Write them as short step lists in `docs/sop/`, one file each:
  - `publish-a-resource.md`
  - `issue-a-refund.md`
  - `publish-a-correction.md`
  - `failed-or-pending-payment.md`
  - `customer-cannot-access.md`

## Data model

| Table | Purpose |
|---|---|
| `audit_logs` | Created in Phase 3. Viewer and full coverage here. |
| `support_notes` | Optional. Internal notes on a customer, restricted and with a retention period. |
| Manual entitlement metadata | Reason and actor are stored on the entitlement's `metadata` and in the audit log. |

## Admin (Filament)

- The permission matrix in [../reference/roles-and-permissions.md](../reference/roles-and-permissions.md) is enforced twice: in the Filament resource (what is shown) and in the domain policy (what is allowed).
- Bulk actions are limited. Destructive or financial bulk actions require confirmation.

## Tests to write

| Required test | File and cases |
|---|---|
| A content reviewer cannot change a price | `tests/Feature/Admin/PermissionMatrixTest.php` — one case per row of the matrix, calling the action or policy directly |
| Support cannot assign an admin role | same file |
| Every refund and access override creates an audit log | `tests/Feature/Admin/AuditCoverageTest.php` — one case for each of the six audited actions in step 14.3 |
| An archived product remains in a historical order | `tests/Feature/Admin/ArchiveTest.php` — archive the product; the order page still renders from the snapshot |
| A correction notice goes to the right people once | `tests/Feature/Admin/CorrectionNoticeTest.php` — only users who received the old version; a second send is refused |
| No hard delete on business records | `tests/Feature/Admin/NoHardDeleteTest.php` — delete policies return false for the listed models |

## Exit checklist

- [ ] A support user can resolve a common access issue without the database console.
- [ ] A content manager can publish next month's programme without developer help.
- [ ] A business admin can see why a programme is not launch-ready.

## Risks and controls

| Risk | Control |
|---|---|
| Powerful admin misuse | Least privilege, audit log and confirmation |
| Developer dependency | Operational workflows built into admin and documented |
| Data exposure | Role-specific customer views and exports |

## Not in this phase

- A full CRM or ticketing suite, unless support volume justifies an integration
