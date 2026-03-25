# n8n Payment Automation Pipeline

This SMM Panel is equipped with an internal webhook system designed to work seamlessly with a local n8n instance (running on the same Docker host) for automated payment verification (eSewa, Khalti, Bank Transfers).

## 1. Outbound Webhook (Laravel -> n8n)
When a user submits a payment transaction ID via the Wallet, Laravel automatically fires a POST request to your n8n webhook.

**Default Target:** `http://localhost:5678/webhook/payment-verify`
*(You can override this by setting `N8N_WEBHOOK_URL` in your `.env` file)*

**JSON Payload Sent to n8n:**
```json
{
  "transaction_internal_id": 45,
  "user_id": 1,
  "user_email": "user@example.com",
  "user_name": "John Doe",
  "amount": 500,
  "transaction_code": "9S123XYZ89",
  "payment_method": "manual",
  "status": "review",
  "timestamp": "2026-03-25 15:30:00"
}
```

## 2. Inbound Webhook (n8n -> Laravel)
Once your n8n workflow parses the bank SMS/email or checks the payment provider API and determines if the transaction code is valid, n8n must send an HTTP POST request back to Laravel to automatically approve the deposit.

### Security
This endpoint is **strictly locked** to internal Docker/Localhost subnets (`127.*`, `172.*`, `10.*`). It cannot be accessed via the public internet.

**Target URL (from inside n8n HTTP Request Node):**
`http://<DOCKER_HOST_IP>:<SMM_PORT>/api/n8n/payment-callback`
*Example:* `http://172.17.0.1:8945/api/n8n/payment-callback`

**Headers Required:**
* `X-N8N-TOKEN`: `secret123` *(Change this via `N8N_SECRET` in `.env`)*
* `Content-Type`: `application/json`

### To Approve Payment:
**JSON Body:**
```json
{
  "transaction_internal_id": 45,
  "status": "completed",
  "message": "Payment verified automatically via n8n SMS parser."
}
```
*This will instantly mark the transaction as "completed", add the funds to the user's wallet, and generate an Activity Log.*

### To Reject Payment:
**JSON Body:**
```json
{
  "transaction_internal_id": 45,
  "status": "rejected",
  "message": "Transaction code not found or amount mismatch."
}
```
