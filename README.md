# SMM Panel with n8n Automation

This repository contains an SMM Panel application with built-in integrations for **n8n Automation**.

## Webhook Architecture

The panel is configured to send various platform events to a single unified n8n webhook. 

### Unified Webhook URL
By default, the application pushes payloads to:
`http://n8n-automation:5678/webhook/smm-events`

### Supported Event Types

The payloads sent to n8n include an `event_type` property so you can easily route them using an n8n Switch Node.

1. **`payment_submit`**
   * Triggered when a user requests a new wallet top-up / payment.
   * Properties include: `transaction_internal_id`, `user_id`, `amount`, `transaction_code`, `payment_method`, etc.

2. **`ticket_create`**
   * Triggered when a user opens a new support ticket.
   * Properties include: `ticket_id`, `user_id`, `subject`, `priority`, `message`, etc.

3. **`ticket_reply`**
   * Triggered when a user replies to an existing support ticket.
   * Properties include: `ticket_id`, `user_id`, `subject`, `message`, etc.

## Setup

Ensure your `.env` file has the proper webhook URL and security secret set:

```env
# URL for outgoing webhooks to n8n
N8N_WEBHOOK_URL="http://n8n-automation:5678/webhook/smm-events"

# Security token for incoming requests from n8n
N8N_SECRET="your_secure_token_here"
```

## Receiving Approvals from n8n

The SMM Panel can accept automated approval or rejection of top-ups directly from n8n.
Send a `POST` request from n8n to `/api/n8n/payment-callback` with the `X-N8N-TOKEN` header matching your `N8N_SECRET` and the following JSON body:

```json
{
  "transaction_internal_id": 123,
  "status": "completed", 
  "message": "Verified via n8n automated flow."
}
```
*(Status must be `completed` or `rejected`)*
