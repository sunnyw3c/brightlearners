# Master QA and acceptance matrix

From Appendix G of the source plan. Each item is proven by a test or a recorded manual check in the phase shown. Run the whole list again before the pilot ([Phase 16](../plan/phase-16-content-uat-pilot.md)).

## Authentication and authorization — Phase 2

- [ ] Register / login / verify / reset works
- [ ] A parent cannot access admin
- [ ] Role permissions are enforced on the server
- [ ] Cross-account IDOR attempts fail
- [ ] Learning-profile access is isolated by parent

## Content — Phase 4

- [ ] An unapproved resource cannot publish
- [ ] A published version is immutable
- [ ] A correction creates a new version
- [ ] A preview is generated and readable
- [ ] The answer key is reviewed
- [ ] The low-ink variant prints correctly

## Commerce — Phase 7

- [ ] The server ignores a manipulated client price
- [ ] An expired coupon is rejected
- [ ] Coupon usage limits are concurrency-safe
- [ ] The order snapshot is stable after a product edit
- [ ] An archived product cannot be newly purchased

## Payments — Phase 8

- [ ] Success
- [ ] Failure
- [ ] Browser closed
- [ ] Webhook before callback
- [ ] Duplicate webhook
- [ ] Invalid signatures
- [ ] Amount mismatch
- [ ] Timeout / pending reconciliation
- [ ] Refund

## Access — Phase 9

- [ ] A paid file is private
- [ ] A signed URL expires
- [ ] An entitlement is required
- [ ] The refund / expiry rule is enforced
- [ ] The correct version is logged

## Membership — Phase 10

- [ ] The 90-day term is exact
- [ ] A future week is locked
- [ ] The correct class programme is shown
- [ ] The flagship exclusion is respected
- [ ] Expiry stops new content
- [ ] The member discount applies only during the active term

## SEO — Phase 12

- [ ] SSR HTML is meaningful
- [ ] The canonical is correct
- [ ] The sitemap excludes drafts
- [ ] Robots is correct
- [ ] A 404 returns a true 404 status
- [ ] No redirect loop
- [ ] JSON-LD has a valid structure

## Performance — Phase 15

- [ ] No major N+1
- [ ] Images are dimensioned and optimised
- [ ] The representative mobile Core Web Vitals budget is met
- [ ] Search response time is acceptable
- [ ] Queue jobs do not block requests

## Operations — Phase 14 and 15

- [ ] A backup restore is proven
- [ ] A failed queue is observable
- [ ] SSR restarts
- [ ] An audit log is created for privileged actions
- [ ] The correction customer list is accurate

## Content pilot — Phase 16

- [ ] All launch files open
- [ ] All answers are independently checked
- [ ] Parent instructions are understandable
- [ ] One future month is in the buffer
- [ ] Support scripts are ready
