# SOP: Failed or Pending Payment Troubleshooting

Standard Operating Procedure for diagnosing and resolving failed, pending, or interrupted payment attempts.

## Purpose

Help support and finance staff diagnose customer payment issues without developer intervention, ensuring parents receive access promptly when payments succeed.

## Payment States Explained

- `pending_payment`: Razorpay order generated, awaiting parent checkout completion or webhook arrival.
- `paid`: Payment captured successfully, order transitioned, entitlements granted.
- `failed`: Bank declined, customer cancelled, or gateway error.
- `refunded` / `partially_refunded`: Monies returned to customer.

## Diagnosis Workflow

1. **Locate the Customer & Order**:
   - Navigate to **Customers → [Parent] → Support View** or **Commerce → Orders**.
   - Check the order status and associated payment records.
2. **Review Payment Details in Admin**:
   - Open **Commerce → Payments** and filter by the customer's email or order number.
   - Inspect:
     - `provider_order_id`: Razorpay Order ID (`order_...`).
     - `provider_payment_id`: Razorpay Payment ID (`pay_...`).
     - `failure_reason`: Error description provided by Razorpay / bank.
3. **Cross-Check with Gateway (Razorpay)**:
   - Search the Razorpay dashboard for the `provider_order_id` or customer email.
   - Scenario A: Payment is marked **Failed** on Razorpay:
     - Advise the customer to try an alternate UPI app or card, or check bank limits.
     - The customer can simply restart checkout from their cart.
   - Scenario B: Payment is marked **Captured** on Razorpay but `pending_payment` in app:
     - Check **System → Webhook Events** for failed or pending `payment.captured` webhooks.
     - If the webhook was missed or delayed, trigger manual verification via payment callback or webhook replay.
4. **Customer Communication**:
   - Reassure the customer that if their account was debited for a failed transaction, banking networks auto-reverse the funds within 5–7 business days.
   - Document any customer contact in the parent's **Support Notes**.
