# Betolisa — Application Documentation

**Handover Document for the App Owner**
*Built with Laravel 13 · PHP 8.3+ · MySQL · Tailwind CSS*

---

## Table of Contents

1. [What the App Does](#1-what-the-app-does)
2. [User Roles](#2-user-roles)
3. [Key Features](#3-key-features)
4. [How the App Works (Flow)](#4-how-the-app-works-flow)
5. [Admin Panel Guide](#5-admin-panel-guide)
6. [Bettor (User) Experience](#6-bettor-user-experience)
7. [Payment System](#7-payment-system)
8. [Telegram Integration](#8-telegram-integration)
9. [Automated Scheduled Tasks](#9-automated-scheduled-tasks)
10. [Email Notifications](#10-email-notifications)
11. [First-Time Setup (After Receiving the App)](#11-first-time-setup-after-receiving-the-app)
12. [Environment Variables (.env)](#12-environment-variables-env)
13. [Admin Settings You Can Change](#13-admin-settings-you-can-change)
14. [Database Overview](#14-database-overview)
15. [Technology Stack](#15-technology-stack)
16. [Day-to-Day Operations](#16-day-to-day-operations)
17. [Troubleshooting Common Issues](#17-troubleshooting-common-issues)

---

## 1. What the App Does

This is a **football tips (tipster) platform**. It allows you (the admin) to publish betting tips and sell premium access to subscribers. Here's the core idea:

- **Free tips** are available to anyone who creates an account.
- **Premium tips** are locked behind a paid subscription.
- When a user subscribes, they automatically receive an invite to your **private Telegram group** where premium tips are shared in real time.
- When their subscription expires, they are automatically **removed** from the Telegram group.

---

## 2. User Roles

The app has exactly two roles:

| Role | Description |
|------|-------------|
| **Admin** | You (the owner). Full access to manage tips, users, subscriptions, and settings. |
| **Bettor** | A registered user/subscriber. Can view tips, pay for premium access, and manage their profile. |

When a new user registers on the site, they are automatically given the **bettor** role.

---

## 3. Key Features

### Admin Features
- Create and manage **football leagues** (e.g., Premier League, La Liga)
- Post **free and premium betting tips** per match
- View and manage all **users** (activate/deactivate, reset passwords, export CSV)
- View and manage all **subscriptions** (cancel, extend, create manually)
- **Approve or reject bank transfer payments** from bettors
- View **revenue reports**
- Configure **subscription price and duration**
- Configure **Paystack API keys** for online payments
- Configure **Telegram bot** for group automation
- View **in-app notifications** (e.g., new subscriptions)

### Bettor Features
- Register and log in
- View free tips without subscribing
- Subscribe via **Paystack (card payment)** or **bank transfer**
- Access premium tips after subscribing
- Automatically join the private Telegram group upon subscription
- View payment history
- Receive email notifications (payment confirmation, expiry warnings, renewal reminders)
- Manage profile and password

### Public Features (No Login Required)
- Browse the public tips listing page (`/tips`)
- View individual tip details

---

## 4. How the App Works (Flow)

### New User Flow
```
User visits site → Registers → Gets "bettor" role → Logs in
→ Views free tips OR clicks "Get Premium"
→ Selects payment method (Paystack card OR bank transfer OR email enquiry)
→ Pays → Subscription activates → Telegram invite sent → Access to premium tips
```

### Subscription Expiry Flow
```
Scheduler runs hourly → Detects expired subscriptions
→ Marks subscription as "expired"
→ Removes user from Telegram group
→ Sends expiry notification email
```

### Admin Posting a Tip
```
Admin logs in → Goes to Tips → Create Tip
→ Selects league, enters teams, odds, tip content
→ Sets type: "free" (public) or "premium" (subscribers only)
→ Sets status: "pending", "won", or "lost"
→ Tip appears on the site immediately
```

---

## 5. Admin Panel Guide

Access the admin panel at: `https://yoursite.com/admin/dashboard`

Log in with the admin account created during setup.

### Dashboard
Shows an overview of active subscriptions, recent users, and quick actions.

### Leagues (`/admin/leagues`)
- Create football leagues with a name, country, and optional logo.
- You can toggle a league active or inactive.
- Inactive leagues will not show in tip creation dropdowns.

### Tips (`/admin/tips`)
- **Create Tip**: Fill in league, home team, away team, your tip content, odds, type (free/premium), and match date.
- **Update Status**: After a match, mark the tip as `won` or `lost`.
- **Bulk Actions**: Select multiple tips and delete or change their status at once.
- Tips marked as **free** are visible to all logged-in users.
- Tips marked as **premium** require an active subscription to view.

### Users (`/admin/users`)
- View all registered users with their subscription status.
- **Toggle status**: Activate or deactivate a user's account (deactivated users cannot log in).
- **Reset password**: Generate a new temporary password for a user.
- **Export CSV**: Download the full user list as a spreadsheet.

### Subscriptions (`/admin/subscriptions`)
- View all subscriptions (active, expired, cancelled).
- **Create manual subscription**: Give a user a free or manually-paid subscription without going through the payment flow.
- **Cancel a subscription**: Ends it early and removes the user from Telegram.
- **Extend a subscription**: Add more days to an existing subscription.
- **Approve/Reject bank transfers**: When a bettor pays via bank transfer and uploads proof, you approve or reject it here.
- **Revenue report**: View total revenue and subscription trends (`/admin/reports/revenue`).

### Settings

#### Subscription Settings (`/admin/settings/subscription`)
- Set the **subscription price** (in Naira, e.g., 5000).
- Set the **subscription duration** in days (e.g., 30 for monthly).
- Set your **Paystack public and secret keys**.

#### Telegram Settings (`/admin/settings/telegram`)
- Set your **Telegram bot token**.
- Set the **Telegram group/channel ID** for the premium group.
- Set your **admin Telegram handle** (shown to users who need help).
- Configure the **free Telegram group popup** (optional) — a banner that appears to users encouraging them to join a free Telegram group.
- View **current channel members** and manually resend invites.
- Test the Telegram bot connection.

### Notifications (`/admin/notifications`)
- In-app notifications for events like new subscriptions.
- Mark individual or all notifications as read.

---

## 6. Bettor (User) Experience

### Registration & Login
Bettors register at `/register`. Email verification is available but not enforced by default. After login, they are redirected to the bettor dashboard.

### Dashboard (`/bettor/dashboard`)
Shows subscription status, days remaining, quick links to premium tips, and Telegram group info.

### Free Tips (`/bettor/tips/free`)
Available to all logged-in users regardless of subscription.

### Premium Tips (`/bettor/tips/premium`)
Only accessible to users with an **active subscription**. Attempting to visit this page without a subscription redirects to the plans page.

### Plans & Payment (`/bettor/plans`)
Displays the current subscription price and duration. Bettor clicks "Subscribe" to go to the payment options page.

### Payment Options (`/bettor/payment`)
Three payment methods are offered:
1. **Paystack (Card/Bank)**: Standard online payment via the Paystack popup. Subscription activates automatically after successful payment.
2. **Bank Transfer**: Bettor manually transfers money to your bank account, then submits a transfer notification form. You (admin) then manually approve it.
3. **Email Enquiry**: Bettor sends a message to your admin email directly from the platform.

### Payment History (`/bettor/payment/history`)
Lists all the bettor's past transactions with their status (pending, successful, failed).

### Profile (`/bettor/profile`)
Bettor can update their name, phone, **Telegram username** (required for group access), and password.

---

## 7. Payment System

The app integrates with **Paystack** (https://paystack.com) for online card payments.

### Paystack Flow
1. User clicks "Pay with Paystack"
2. Paystack popup opens — user enters card details or pays via bank transfer through Paystack
3. After payment, Paystack redirects to the callback URL
4. App verifies the payment with Paystack's API
5. If successful, a subscription is created and the Telegram invite is sent

### Bank Transfer Flow
1. User fills the bank transfer form with amount and details
2. A pending transaction is created
3. Admin sees the pending transaction in `/admin/subscriptions`
4. Admin clicks "Approve" → subscription activates, Telegram invite sent
5. Admin clicks "Reject" → transaction is marked as failed

### Keys Required
You need to get these from your Paystack dashboard (https://dashboard.paystack.com):
- **Public Key** (starts with `pk_`)
- **Secret Key** (starts with `sk_`)

Use **test keys** while testing, then switch to **live keys** when going live.

---

## 8. Telegram Integration

The app has a Telegram bot that **automatically manages your premium group membership**.

### How It Works
1. Admin creates a Telegram bot via [@BotFather](https://t.me/BotFather) on Telegram.
2. The bot is added as an **admin** to the premium tips Telegram group.
3. When a user subscribes, the bot sends them a **personal, one-time invite link** via direct message.
4. When a subscription expires, the bot **removes the user** from the group.

### Important Requirements
- The user **must have messaged the bot first** (start a chat with it) before the bot can DM them. Otherwise the invite link cannot be delivered.
- The user must provide their **correct Telegram username** (including the `@`) in their profile settings.
- The bot must be an **admin** in the group with permission to invite users and remove members.

### Setup Steps
1. Open Telegram → Search for `@BotFather` → Send `/newbot`
2. Follow the instructions to get your **Bot Token** (looks like `1234567890:AAABBBCCC...`)
3. Add the bot to your premium Telegram group and make it an **admin**
4. Get the group ID (usually starts with `-100...`) — you can find it using the bot or an ID-finder bot
5. Enter both values in the admin Telegram Settings page

### Free Telegram Group (Optional)
You can also configure a separate **free Telegram group**. When enabled, a popup banner appears for users encouraging them to join the free group. Configure this in Admin → Settings → Telegram → Free Group section.

---

## 9. Automated Scheduled Tasks

The app runs two scheduled tasks automatically:

| Task | Schedule | What It Does |
|------|----------|--------------|
| `subscriptions:check-expired` | Every hour | Finds subscriptions past their end date, marks them expired, removes users from Telegram group |
| `notifications:send-scheduled` | Daily at 9:00 AM | Sends expiry warning emails (3 days before), expiry notification emails, and renewal reminder emails |

### How to Keep the Scheduler Running

On **Windows (Laragon/Local)**: Double-click `run-scheduler.bat` or set it up in Windows Task Scheduler to run every minute:
```
Action: Run program
Program: C:\laragon\www\betolisa2\run-scheduler.bat
Schedule: Every 1 minute
```

On **Linux (Production Server)**: Add this to your cron jobs (`crontab -e`):
```
* * * * * php /path/to/betolisa2/artisan schedule:run >> /dev/null 2>&1
```

**If the scheduler is not running**, subscriptions will NOT auto-expire and users will NOT be removed from Telegram on time.

---

## 10. Email Notifications

The app sends the following emails automatically:

| Email | Sent To | When |
|-------|---------|------|
| Welcome Email | Bettor | When they register |
| Payment Confirmation | Bettor | When subscription activates |
| New Subscription Alert | Admin | When any user subscribes |
| Expiry Warning | Bettor | 3 days before subscription ends |
| Subscription Expired | Bettor | When subscription ends |
| Renewal Reminder | Bettor | After expiry, encouraging them to renew |

### Email Configuration
Email settings are in the `.env` file. For production, you can use:
- **SMTP** (e.g., Mailtrap for testing, or your email provider)
- **Mailgun**, **Postmark**, **SES** (AWS), or any Laravel-compatible mail driver

---

## 11. First-Time Setup (After Receiving the App)

Follow these steps to get the app running on your production server:

### Step 1: Upload the code
Upload the entire project to your server (e.g., via FTP, Git, or a control panel).

### Step 2: Create the `.env` file
Copy `.env.example` to `.env` and fill in the values (see Section 12 below).

### Step 3: Install dependencies
Run these commands in the project folder:
```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

### Step 4: Generate app key
```bash
php artisan key:generate
```

### Step 5: Set up the database
Create a MySQL database, then run:
```bash
php artisan migrate --seed
```
This creates all tables and inserts:
- Two roles: `admin` and `bettor`
- A default admin user: `admin@tipster.com` / password: `password`
- Sample football leagues

**Important**: Change the admin email and password immediately after first login!

### Step 6: Set folder permissions
```bash
chmod -R 775 storage bootstrap/cache
```

### Step 7: Set up the cron job (for automation)
```bash
crontab -e
# Add this line:
* * * * * php /path/to/your/project/artisan schedule:run >> /dev/null 2>&1
```

### Step 8: Configure settings in admin panel
1. Log in as admin
2. Go to Settings → Subscription → set your price and Paystack keys
3. Go to Settings → Telegram → set your bot token and group ID

---

## 12. Environment Variables (.env)

These are the critical variables in your `.env` file:

```env
# App
APP_NAME="Betolisa"
APP_URL=https://yoursite.com
APP_ENV=production
APP_DEBUG=false

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# Email
MAIL_MAILER=smtp
MAIL_HOST=smtp.yourprovider.com
MAIL_PORT=587
MAIL_USERNAME=your@email.com
MAIL_PASSWORD=yourpassword
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yoursite.com
MAIL_FROM_NAME="Betolisa Tips"

# Paystack (also configurable in Admin Settings)
PAYSTACK_PUBLIC_KEY=pk_live_xxxxx
PAYSTACK_SECRET_KEY=sk_live_xxxxx

# Queue (set to "sync" if you don't have a queue worker)
QUEUE_CONNECTION=sync
```

> **Note**: Paystack keys and Telegram settings can also be entered directly in the Admin Panel (Admin → Settings), which takes priority over the `.env` values.

---

## 13. Admin Settings You Can Change

All of these are changeable **without editing any code**, directly from the admin panel:

| Setting | Where | Description |
|---------|-------|-------------|
| Subscription price | Admin → Settings → Subscription | Price in Naira (e.g., 5000) |
| Subscription duration | Admin → Settings → Subscription | How many days access lasts (e.g., 30) |
| Paystack public key | Admin → Settings → Subscription | Your Paystack public key |
| Paystack secret key | Admin → Settings → Subscription | Your Paystack secret key |
| Telegram bot token | Admin → Settings → Telegram | Your bot's API token |
| Telegram group ID | Admin → Settings → Telegram | Your premium group chat ID |
| Admin Telegram handle | Admin → Settings → Telegram | Your @username (shown to users needing help) |
| Free Telegram group | Admin → Settings → Telegram | Optional free group banner shown to users |

---

## 14. Database Overview

The app uses the following main database tables:

| Table | Purpose |
|-------|---------|
| `users` | All registered users (both admins and bettors) |
| `roles` / `permissions` | Spatie roles (admin, bettor) |
| `leagues` | Football leagues (e.g., Premier League) |
| `tips` | Individual betting tips |
| `subscriptions` | Subscription records per user |
| `transactions` | Payment records for every payment attempt |
| `settings` | Key-value configuration store (subscription price, Telegram settings, etc.) |
| `notifications` | In-app notifications stored per user |

---

## 15. Technology Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 13 (PHP 8.3+) |
| Authentication | Laravel Breeze |
| Roles & Permissions | Spatie Laravel Permission |
| Frontend | Tailwind CSS + Vite |
| Payment Gateway | Paystack |
| Messaging | Telegram Bot API |
| Database | MySQL |
| Email | Laravel Mail (SMTP/Mailgun/etc.) |
| Task Scheduling | Laravel Scheduler (Artisan) |

---

## 16. Day-to-Day Operations

Here's what you will typically do as the admin on a regular basis:

### Every Day
- Log in to the admin panel
- Check **notifications** for new subscriptions or alerts
- Post new **tips** for upcoming matches
- Update tip status (won/lost) after matches finish

### As Needed
- **Approve pending bank transfers** from the subscriptions page
- **Extend or cancel subscriptions** manually when needed
- **Create free subscriptions** for VIP users or testers
- **Export user list** for marketing or records

### Occasionally
- Review **revenue reports**
- Adjust **subscription price or duration**
- Update **Paystack API keys** (when rotating for security)
- Monitor **Telegram group membership** against active subscribers

---

## 17. Troubleshooting Common Issues

### "User did not receive Telegram invite"
- Check that the user's **Telegram username** is correctly set in their profile (must include `@`)
- The user must have **started a chat** with your Telegram bot first — ask them to find your bot on Telegram and press Start
- Verify the bot token and group ID are correct in Admin → Settings → Telegram
- Go to Admin → Settings → Telegram → scroll to Channel Members → click "Resend Invite" for that user

### "Payment went through but subscription didn't activate"
- Check the Laravel logs at `storage/logs/laravel.log`
- Verify Paystack keys are correct (test vs live keys)
- Confirm the Paystack callback URL is accessible from the internet (not a local URL)

### "Subscriptions are not auto-expiring"
- The cron job or Windows Task Scheduler may not be running
- Run manually to test: `php artisan subscriptions:check-expired`
- Check that the scheduler is set up correctly (see Section 9)

### "Emails are not being sent"
- Check your `.env` mail settings
- Test with: `php artisan tinker` → `Mail::raw('Test', fn($m) => $m->to('you@example.com')->subject('Test'));`
- Check `storage/logs/laravel.log` for mail errors

### "Admin cannot log in"
- Default credentials (from seeder): `admin@tipster.com` / `password`
- Reset via database: `php artisan tinker` → `User::where('email','admin@tipster.com')->first()->update(['password' => bcrypt('newpassword')])`

### "Page shows 500 error"
- Set `APP_DEBUG=true` temporarily to see the error
- Check `storage/logs/laravel.log`
- Ensure `storage/` and `bootstrap/cache/` are writable
- Run `php artisan config:clear` and `php artisan cache:clear`

---

*This documentation was written at the time of handover. For technical changes or new features, consult a Laravel developer.*
