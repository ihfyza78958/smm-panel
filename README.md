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

## Sending Ticket Replies from n8n

You can automatically send an admin reply back to the user's ticket from n8n.
Send a `POST` request to `/api/n8n/ticket-reply` with your `X-N8N-TOKEN` header and the following body:

```json
{
  "ticket_id": 45,
  "message": "Thank you for your message. We have processed your request.",
  "status": "closed" 
}
```
*(Optional `status` can be `open`, `answered`, or `closed`)*

---

## Production Deployment (GitOps Workflow)

The deployment for this SMM Panel on the production server is fully automated via Git.

### 1. Develop Locally
Make your code changes, test them, and then commit them to the repository:
```bash
git add .
git commit -m "Describe your changes"
git push myfork main
```

### 2. Deploy to Production
Log into your production server (`192.168.10.5`):
```bash
ssh -p 2222 pro@192.168.10.5
```

Navigate to the project directory and run the deployment script:
```bash
cd /opt/docker/smm-panel
sudo ./deploy.sh
```

**What `deploy.sh` does:**
- `git pull origin main` (Securely fetches the latest code using the Deploy Key)
- Rebuilds Docker images and restarts containers with zero-downtime using `docker compose`
- Runs any missing database migrations (`php artisan migrate --force`)
- Clears and rebuilds Laravel caches (config, events, routes, views)

Your changes will be live immediately after the script finishes!
