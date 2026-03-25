# SMM Panel 🚀

A modern, highly optimized, dockerized Laravel SMM Panel built for the Nepali and Indian markets.

## Key Features
- **Fully Dockerized** for instant, identical deployments anywhere
- **eSewa / Khalti / Bank** Payment Automation (via n8n Webhooks)
- **Automatic Nightly Sync** (Pulls new services, adjusts pricing, removes dead services)
- **Built-in SEO Blog** engine for organic ranking
- **Mobile-first UI**

---

## 🛠️ Installation (First Time Setup on New Server)

### Prerequisites
- A Linux Server (Ubuntu 22.04+ recommended)
- **Docker** & **Docker Compose** installed
- **Git** installed

### Step 1: Clone the repository
```bash
git clone https://github.com/ioprakash/smm-panel.git
cd smm-panel
```

### Step 2: Configure Environment
```bash
cp .env.example .env
nano .env
```
*Update your database credentials, domain name (`APP_URL`), Google Client IDs, and n8n webhook keys.*

### Step 3: Run the Auto-Installer
```bash
chmod +x install.sh
./install.sh
```
*This script will automatically build the containers, install PHP/NPM dependencies, run database migrations, seed the initial admin account, link the storage, and optimize the framework.*

---

## 🚀 Production Deployment (Updating Existing Server)

When you make changes to the code on GitHub and want to update your live production server, you do **not** need to manually clear caches or restart containers.

Simply SSH into your server, navigate to the folder, and run the deploy script:

```bash
cd /path/to/smm-panel
./deploy.sh
```

**What `deploy.sh` does automatically:**
1. Pulls the latest code from the `main` branch.
2. Rebuilds any Docker containers in the background without downtime.
3. Safely runs any new database migrations.
4. Clears and aggressively recaches the routes, views, and configs for maximum speed.

---

## Payment Automation (n8n Integration)
This project supports automated payment verification via local n8n webhooks. 
When users submit transaction codes, it securely triggers local verification flows. 
For setup instructions, headers, and payload structures, please read the [N8N_AUTOMATION.md](N8N_AUTOMATION.md) guide.
