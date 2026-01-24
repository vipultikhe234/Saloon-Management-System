# 🚀 Quick Start Guide - Saloon Management System

## ⚡ Fast Setup (5 Minutes)

### Option 1: Automated Setup (Recommended)

**Note**: This project has been configured to be compatible with Node.js v14.15+. If you have a newer version, you can update dependencies, but the current setup works out of the box.

**Windows:**
```bash
# Just double-click or run:
setup.bat
```

**Linux/Mac:**
```bash
# Make executable and run:
chmod +x setup.sh
./setup.sh
```

The script will:
✅ Install all dependencies
✅ Setup environment
✅ Create database
✅ Run migrations
✅ Seed demo data
✅ Build assets

### Option 2: Manual Setup

```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup environment
copy .env.example .env
php artisan key:generate

# 3. Configure database in .env
DB_CONNECTION=mysql
DB_DATABASE=saloon_management
DB_USERNAME=root
DB_PASSWORD=

# 4. Create database
mysql -u root -p
CREATE DATABASE saloon_management;
EXIT;

# 5. Run migrations & seeders
php artisan migrate:fresh --seed

# 6. Build frontend
npm run build

# 7. Start server
php artisan serve
```

## 🔑 Login & Test

### Visit: http://localhost:8000

### Test Accounts:

**Super Admin:**
- Email: `admin@saloon.com`
- Password: `password`
- Dashboard: `/super-admin/dashboard`

**Saloon Admin:**
- Email: `saloon@saloon.com`
- Password: `password`
- Dashboard: `/saloon-admin/dashboard`

**Customer:**
- Email: `user@saloon.com`
- Password: `password`
- Dashboard: `/user/dashboard`

## 📱 Quick Feature Tour

### As Super Admin
1. Login with admin credentials
2. View system-wide statistics
3. Go to Saloons → View all saloons
4. Security: Customer data is hidden for privacy
5. Test creating a new saloon

### As Saloon Admin
1. Login with saloon admin credentials
2. View your saloon dashboard
3. Go to Queue → Manage active queue
4. Go to Services → Add a new service
5. Go to Staff → Add staff member
6. View today's appointments

### As Customer
1. Logout and go to home page
2. Use the **State** and **City** dropdowns to filter local stylists
3. Browse priority-sorted saloons based on your location
3. Click "View Details" on a saloon
4. Browse services and Join Queue or Book
5. View your status in the User Dashboard

## 🎨 Customize

### Change Colors
Edit `resources/views/layouts/dashboard.blade.php`:
```css
:root {
    --primary: #your-color;
    --secondary: #your-color;
}
```

### Change App Name
Edit `.env`:
```
APP_NAME="Your Salon Name"
```

### Add Logo
Replace sidebar header in `resources/views/layouts/partials/sidebar.blade.php`

## 🐛 Troubleshooting

### Database Connection Error
```bash
# Check .env database settings
# Ensure MySQL is running
# Verify database exists
```

### Permission Errors
```bash
# Windows: Give full control to storage folders
# Linux/Mac:
chmod -R 775 storage bootstrap/cache
```

### Assets Not Loading
```bash
npm run build
php artisan view:clear
# Hard refresh browser (Ctrl+Shift+R)
```

### Migration Fails
```bash
php artisan config:clear
php artisan migrate:fresh --seed
```

## 📚 Next Steps

1. ✅ **Setup Complete** - You're ready to go!
2. 📖 **Read README.md** - Full installation guide
3. 📘 **Read DOCUMENTATION.md** - Technical details
4. 🎨 **Customize** - Make it your own
5. 🚀 **Deploy** - Push to production

## 🆘 Need Help?

1. Check **README.md** for detailed installation
2. Review **DOCUMENTATION.md** for technical info
3. Look at **PROJECT_SUMMARY.md** for feature list
4. All migrations are in `database/migrations/`
5. All models are in `app/Models/`

## 💡 Tips

- Use **SQLite** for quick testing (no MySQL needed)
- Run `php artisan migrate:fresh --seed` to reset database
- Use `npm run dev` for development with hot reload
- Check routes with `php artisan route:list`
- View logs in `storage/logs/laravel.log`

## ⚡ Development Commands

```bash
# Start dev server
php artisan serve

# Watch for asset changes
npm run dev

# Clear all caches
php artisan optimize:clear

# Run migrations
php artisan migrate

# Fresh install
php artisan migrate:fresh --seed

# View routes
php artisan route:list
```

## 🎯 You're All Set!

Your advanced Saloon Management System is ready to use! 🎉

**Home Page**: http://localhost:8000  
**Admin Login**: http://localhost:8000/login

---

**Made with ❤️ using Laravel 12**
