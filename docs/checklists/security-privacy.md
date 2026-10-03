# Security and privacy checklist

From Appendix I of the source plan. Each item is built in the phase shown and verified as a whole in [Phase 15](../plan/phase-15-hardening.md).

## Application

- [ ] HTTPS only — Phase 15
- [ ] CSRF enabled (the Razorpay webhook route is the only exception) — Phase 1, 8
- [ ] Escaped output / controlled rich-text sanitisation — Phase 12
- [ ] Server-side validation — every phase
- [ ] Policies for every ownership-sensitive model — every phase
- [ ] Rate limits on auth / search / coupon / download — Phase 2, 15
- [ ] Secure session and cookie settings — Phase 15
- [ ] No secrets in the Vite / frontend bundle — Phase 1, 8

## Payments

- [ ] The Razorpay order is created on the server — Phase 8
- [ ] The callback signature is verified — Phase 8
- [ ] The webhook raw-body signature is verified — Phase 8
- [ ] Idempotent event handling — Phase 8
- [ ] Amount and currency are checked against the internal order — Phase 8
- [ ] Secrets are redacted in logs — Phase 8, 15

## Files

- [ ] Paid files are private — Phase 4, 9
- [ ] MIME / type validation — Phase 4
- [ ] The file name is not trusted for execution — Phase 4
- [ ] Short-lived signed URLs — Phase 9
- [ ] The public preview is separated from the protected original — Phase 4
- [ ] Version history is retained — Phase 4

## Admin

- [ ] 2FA — Phase 1, 2
- [ ] Least privilege — Phase 2, 14
- [ ] Audit trail — Phase 3, 14
- [ ] No shared admin accounts — Phase 2
- [ ] Sensitive bulk actions are confirmed — Phase 14
- [ ] Refund and role permissions are restricted — Phase 2, 8

## Privacy

- [ ] The parent is the account holder — Phase 2
- [ ] Child data is minimised — Phase 2
- [ ] Marketing consent is separate from transactional messages — Phase 2, 11
- [ ] The retention and deletion process is documented — Phase 2, 13, 16
- [ ] No child behavioural advertising or tracking design — Phase 13
- [ ] The privacy notice is reviewed before launch — Phase 16

## Infrastructure

- [ ] Patch cadence — Phase 15
- [ ] Firewall and minimal exposed services — Phase 15
- [ ] The database is not publicly exposed — Phase 1, 15
- [ ] Backups are encrypted and access-controlled — Phase 15
- [ ] A restore has been tested — Phase 15
- [ ] Uptime and error alerts — Phase 15
- [ ] Log rotation and access controls — Phase 15
