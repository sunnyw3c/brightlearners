# SOP: Issue a Refund

Standard Operating Procedure for handling refund requests and issuing payment refunds.

## Purpose

Provide clear rules and an audited workflow for customer refunds while maintaining accurate financial records and automated entitlement adjustments.

## Roles & Separation of Duties (Decision D-15)

- **Customer Support**: Can review customer order and request a refund with a documented reason.
- **Finance / Super Admin**: Holds `refunds.issue` permission to approve and execute financial refunds via the Razorpay gateway.

## Refund Rules & Constraints

1. **Pilot Policy**: Full refunds are supported for individual products and membership purchases during the pilot.
2. **Maximum Amount**: The refund amount cannot exceed the payment amount minus any prior partial refunds.
3. **Access Revocation**: When a payment is fully refunded, all entitlements linked to that order are revoked immediately (`RevokeEntitlementsOnRefund`).

## Step-by-Step Procedure

1. **Locate Payment**:
   - Navigate to **Commerce → Payments** or find the payment from **Customers → [Parent] → Support View**.
   - Check that the payment status is `paid` (captured).
2. **Review Refund Request**:
   - Verify the customer's stated problem, contact timestamp, and support notes.
3. **Trigger Refund**:
   - On the payment record, click **Issue Refund**.
   - Enter the refund amount in integer paise (e.g. `49900` for ₹499.00).
   - Enter a mandatory **Refund Reason** (e.g. "Customer requested cancellation within 7 days", "Accidental duplicate order").
4. **Execution & Verification**:
   - The system calls the Razorpay refund API and transitions the payment to `refunded` or `partially_refunded`.
   - The associated order status transitions to `Refunded`.
   - Event `RefundCompleted` triggers entitlement revocation.
   - Audit trail entry (`refund.issued`) is logged with actor, amount, and reason.
