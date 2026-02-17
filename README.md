# 🚀 CRM Portal (Laravel)
A Laravel-based Customer Relationship Management (CRM) Portal.
This guide explains how to set up and run the project from GitHub to localhost and production server.
---

# 📋 1. System Requirements

Make sure the following are installed on your system:

- PHP 8.1 or higher
- Composer
- MySQL / XAMPP / WAMP
- Node.js & NPM
- Git

Check versions:

```bash
php -v
composer -v
node -v
npm -v
git --version

git clone https://github.com/saukatali5200/sembark.git
cd sembark

composer install

php artisan migrate

php artisan db:seed

php artisan serve

Frontend
Server running on http://127.0.0.1:8000

Admin Portal
Server running on http://127.0.0.1:8000/adminpnlx
