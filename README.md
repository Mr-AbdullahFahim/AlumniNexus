# AlumniNexus

AlumniNexus is a premium networking platform built to bridge the gap between students, successful alumni, and corporate sponsors. It allows users to build profiles, connect with peers, and participate in a unique gamified sponsorship and bidding system to gain featured visibility.

## 🚀 Key Features

*   **Role-Based Access**: Specialized dashboards and functionalities for three distinct roles: Students, Alumni, and Corporate Sponsors.
*   **Alumni Directory**: A searchable, filterable global directory of alumni.
*   **Direct Sponsorships**: Corporate sponsors can directly fund alumni via virtual funds.
*   **Gamified Bidding System**: Alumni use sponsorship funds to place blind bids in daily cycles. At the end of the day, the highest bidder wins the "Featured Alumni" spot.
*   **JWT Authentication**: Secure stateless authentication using HttpOnly cookies and JSON Web Tokens.
*   **Modern UI**: Built with Tailwind CSS and Alpine.js for a lightning-fast, highly interactive, and responsive user experience.
*   **Hotwire Turbo Integration**: SPA-like instant page transitions without full page reloads.

## 🛠️ Technology Stack

*   **Backend Framework**: CodeIgniter 4 (PHP 8.1+)
*   **Database**: MySQL
*   **Frontend Styling**: Tailwind CSS (CDN/CLI)
*   **Frontend Interactivity**: Alpine.js
*   **Authentication**: Firebase JWT (`firebase/php-jwt`)

---

## 💻 Local Development Setup

Follow these steps to get the project running on your local machine for development.

### 1. Prerequisites
Ensure you have the following installed:
*   PHP 8.1 or newer (with `intl`, `mbstring`, `json`, `mysqlnd` extensions enabled)
*   Composer
*   MySQL Server (e.g., XAMPP, WAMP, or standalone)

### 2. Installation
**Must Do's when cloning:**
1. Clone the repository: `git clone <repository_url>`
2. Navigate into the directory: `cd AlumniNexus`
3. Install the PHP dependencies:
```bash
composer install
```

### 3. Environment Configuration
**Must Do's for Environment Setup:**
1. Copy the `.env.example` file to create your `.env` file (e.g., `cp .env.example .env`).
2. Open the `.env` file at the root of the project and ensure you configure your specific database credentials, SMTP email settings, and JWT secret.
Configure the following environment variables:

```ini
# Environment
CI_ENVIRONMENT = development

# Database Configuration
database.default.hostname = localhost
database.default.database = your_database_name
database.default.username = your_database_user
database.default.password = your_database_password
database.default.DBDriver = MySQLi

# JWT Configuration
JWT_SECRET_KEY = generate_a_very_long_random_string_here
JWT_ALG = HS256
JWT_TTL = 900
```

### 4. Database Migrations & Seeding
Run the migrations to create the database tables, and run the seeders to populate the database with dummy data and test users.

**Must Do's for Seeding Data & Initialization:**
*   You **must** run `RoleSeeder` first before any other seeders, as user accounts depend on these roles existing.
*   After `RoleSeeder`, you can safely run `UserSeeder` and other seeders.
*   **Crucial Setup Step**: After running migrations and seeders, you *must* run the settlement script once to initialize the very first bidding cycle. Without this, the bidding system won't start.

```bash
php spark migrate
php spark db:seed RoleSeeder
php spark db:seed UserSeeder
php spark db:seed DummyAlumniSeeder
php spark db:seed BiddingTestSeeder
php spark db:seed WinningStatsSeeder

# Initialize the first bidding cycle
php spark bids:settle
```

### 5. Start the Development Server
```bash
php spark serve
```
The application will be accessible at `http://localhost:8080`.

---

## 🌍 Production Deployment Guide

Deploying AlumniNexus to a production server requires a few strict security and performance configurations.

### 1. Environment Configuration
In your production `.env` file, ensure the following is set to disable debug tools and error display:
```ini
CI_ENVIRONMENT = production
```
**Critical:** Ensure your `JWT_SECRET_KEY` is completely unique, long, and securely generated.

### 2. Web Server Configuration
The web server's document root MUST point to the `public/` directory inside the project, NOT the project root. This prevents public access to your backend application code, environment variables, and configuration files.

**Example Apache VirtualHost:**
```apache
<VirtualHost *:80>
    ServerName alumninexus.com
    DocumentRoot /var/www/alumninexus/public
    
    <Directory /var/www/alumninexus/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 3. Folder Permissions
Ensure the web server (e.g., `www-data` or `apache`) has write access to the `writable/` directory. This is used for caching, logs, and sessions:
```bash
chmod -R 777 writable/
```
*(Note: Use more restrictive permissions like 755 or 775 depending on your specific server user/group setup).*

---

## ⏱️ Cron Job Configuration (Critical)

The core feature of AlumniNexus is the daily Bidding Cycle. Alumni place bids throughout the day, and at **6:00 PM (18:00)**, the cycle closes, the system evaluates the highest bidder, deducts their sponsorship funds, and crowns them the "Featured Alumni".

Because PHP is request-driven, this process **must** be triggered automatically by the server's task scheduler (Cron) every day.

### Setting up the Cron Job (Linux/Ubuntu)
We have written a custom CodeIgniter Spark Command (`bids:settle`) that handles all of this math and database updating safely.

1. Open your server's crontab:
```bash
crontab -e
```

2. Add the following line to run the settlement script exactly at 18:00 (6 PM) every single day:
```bash
0 18 * * * cd /path/to/your/project && php spark bids:settle >> /path/to/your/project/writable/logs/cron.log 2>&1
```

*Replace `/path/to/your/project` with the actual absolute path to your AlumniNexus folder on the server.*

**What this does:**
* `0 18 * * *`: Runs at minute 0 past hour 18 (6:00 PM).
* `cd /path... && php spark bids:settle`: Changes into the project directory and executes the CLI command.
* `>> .../cron.log 2>&1`: Logs any output or errors to a log file inside your `writable` directory so you can debug if the settlement fails.

*(If you are hosting on cPanel or a shared host, look for the "Cron Jobs" icon in your control panel and use the same command structure).*

---

## 🧪 Manual Testing in Development

During development, you don't need to wait until 6:00 PM to test the core loop of the platform. You can simulate the entire process manually.

### 🔑 Test Accounts
If you have run the database seeders (specifically `UserSeeder`), the following test accounts are available to help you test different roles. The password for all these accounts is `#String123`.

*   **System Admin**: `admin@alumninexus.com`
*   **Alumni**: `alumni@example.com`
*   **Student**: `student@example.com`
*   **Corporate Sponsor**: `sponsor@example.com`

### Step-by-Step Testing Loop:

1. **Log In as a Corporate Sponsor**
   - Use one of the sponsor accounts generated by the `UserSeeder`.
   - Go to the **Alumni Directory**, find an alumni profile, and click **Sponsor**.
   - Enter an amount (e.g., $100) and submit. This adds to the alumni's bidding power.

2. **Log In as the Sponsored Alumni**
   - Open an incognito window or log out, then log in using the sponsored Alumni's account.
   - On the **Alumni Dashboard**, you will see your "Available Funds" have increased.
   - Scroll down to the **Blind Bidding** section and submit a bid for the current cycle.

3. **Trigger the Settlement Script Manually**
   - Open your terminal/command prompt.
   - Navigate to the root of your project.
   - Run the settlement command manually:
     ```bash
     php spark bids:settle
     ```
   - *This forces the system to immediately close the current cycle, evaluate all bids, declare winners, deduct funds, and start the next cycle.*

4. **Verify the Results**
   - Refresh the Alumni Dashboard. You should see the cycle has shifted to the next day.
   - Check the **Global Cycle History** modal to see the winner of the cycle you just forced to close!
