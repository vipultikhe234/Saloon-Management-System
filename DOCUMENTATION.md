# Saloon Management System - Technical Documentation

## Project Overview

This is an advanced, production-ready Laravel 12 web application designed to manage salons/saloons with a comprehensive three-panel system.

## Architecture

### Multi-Panel System

#### 1. Super Admin Panel (`/super-admin/*`)
**Purpose**: Complete system administration  
**Role**: `super_admin`  
**Access**: Full control over the entire platform

**Features**:
- Dashboard with system-wide analytics (including Paid/Unpaid saloon tracking)
- Saloon management (CRUD, verify, activate/deactivate)
- Admin User management (Restricted to non-customer roles for privacy)
- System settings and configuration
- Revenue and booking reports
- Category management

**Key Routes**:
- `GET /super-admin/dashboard` - Main dashboard
- `GET /super-admin/saloons` - List all saloons
- `GET /super-admin/users` - List all users
- `POST /super-admin/saloons/{saloon}/verify` - Verify a saloon
- `POST /super-admin/saloons/{saloon}/toggle-status` - Toggle saloon status

#### 2. Saloon Admin Panel (`/saloon-admin/*`)
**Purpose**: Individual saloon management  
**Role**: `saloon_admin`  
**Access**: Manage their own saloon only

**Features**:
- Saloon-specific dashboard
- Queue Management (Join/Start/Complete/Cancel)
- Service management
- Staff management and service assignment
- Appointment management
- Review moderation
- Coupon creation
- Saloon-specific reports

**Key Routes**:
- `GET /saloon-admin/dashboard` - Saloon dashboard
- `GET /saloon-admin/services` - Manage services
- `GET /saloon-admin/staff` - Manage staff
- `GET /saloon-admin/appointments` - View appointments
- `POST /saloon-admin/appointments/{appointment}/update-status` - Update appointment status

#### 3. User/Customer Panel (`/user/*`)
**Purpose**: Customer booking and management  
**Role**: `user`  
**Access**: Personal appointments and bookings

**Features**:
- Browse saloons with subscription-based priority
- State and City based discovery filters
- Join Queue or Book Appointments
- View appointment/queue history
- Rate and review services
- Manage personal profile
- View available coupons

**Public Routes**:
- `GET /` - Landing page (with State/City filters)
- `GET /saloons` - Browse all saloons (with advanced filtering)
- `GET /saloon/{slug}` - View saloon details
- `GET /register/saloon` - Specialized business registration flow
- `POST /register/saloon` - Process business registration

- `GET /user/dashboard` - User dashboard
- `POST /user/book` - Book appointment
- `POST /user/queue/join/{saloon}` - Join queue
- `GET /user/queue/status/{appointment}` - Status
- `GET /user/appointments` - View appointments
- `POST /user/appointment/{appointment}/cancel` - Cancel appointment

## Database Schema

### Core Tables

#### users
- Primary authentication table
- Role field: `super_admin`, `saloon_admin`, `user`
- Fields: name, email, password, role, is_active, phone, address, profile_image

#### saloons
- Stores saloon information
- Relationships: belongsTo User (owner), hasMany Services, Staff, Appointments
- Key fields: name, slug, address, city, state, rating, is_active, is_verified
- Geographic data: latitude, longitude
- Business hours: opening_time, closing_time, working_days (JSON)

#### categories
- Service categories
- Fields: name, slug, icon, image, sort_order

#### services
- Saloon services with pricing
- Relationships: belongsTo Saloon, Category
- Fields: name, price, discounted_price, duration_minutes, gender (male/female/unisex)

#### staff
- Saloon employees
- Relationships: belongsTo Saloon, belongsToMany Services
- Fields: name, email, phone, specialization, rating, commission_percentage
- Schedule: working_days (JSON), shift_start, shift_end

#### appointments
- Booking records
- Auto-generated appointment_number
- Relationships: belongsTo User, Saloon, Staff, Service
- Status: pending, confirmed, in_progress, completed, cancelled, no_show
- Pricing: total_amount, discount_amount, final_amount

#### payments
- Transaction records
- Auto-generated transaction_id
- Payment methods: cash, card, upi, wallet, online
- Status: pending, completed, failed, refunded
- Payment gateway fields: razorpay_payment_id, razorpay_order_id

#### reviews
- Customer feedback
- Relationships: belongsTo User, Saloon, Appointment, Staff
- Moderation: is_approved field
- Support for images (JSON array)

#### coupons
- Discount management
- Types: percentage, fixed
- Validation: usage_limit, usage_per_user, min_purchase_amount
- Can be global or saloon-specific

#### notifications
- User notifications
- Types: appointment, payment, promotion, system
- Read status tracking

#### settings
- System configuration
- Key-value pairs with type and group

## Authentication & Authorization

### Middleware System

**RoleMiddleware** (`app/Http/Middleware/RoleMiddleware.php`)
- Checks if user is authenticated
- Validates user is active
- Verifies user has required role
- Usage: `Route::middleware(['auth', 'role:super_admin'])`

### Login Flow

1. User submits login credentials
2. `AuthenticatedSessionController@store` authenticates
3. Checks user role
4. Redirects to appropriate dashboard:
   - Super Admin → `/super-admin/dashboard`
   - Saloon Admin → `/saloon-admin/dashboard`
   - User → `/user/dashboard`

## Models & Relationships

### User Model
```php
// Methods
isSuperAdmin()      // Check if super admin
isSaloonAdmin()     // Check if saloon admin
isUser()            // Check if regular user

// Relationships
saloons()           // hasMany Saloon (as owner)
appointments()      // hasMany Appointment
payments()          // hasMany Payment
reviews()           // hasMany Review
notifications()     // hasMany Notification
```

### Saloon Model
```php
// Auto-generates slug from name

// Relationships
owner()             // belongsTo User
services()          // hasMany Service
staff()             // hasMany Staff
appointments()      // hasMany Appointment
reviews()           // hasMany Review
coupons()           // hasMany Coupon
```

### Service Model
```php
// Attributes
getFinalPriceAttribute()  // Returns discounted_price ?? price

// Relationships
saloon()            // belongsTo Saloon
category()          // belongsTo Category
staff()             // belongsToMany Staff
appointments()      // hasMany Appointment
```

### Appointment Model
```php
// Auto-generates appointment_number (APT-XXXXX)

// Relationships
user()              // belongsTo User
saloon()            // belongsTo Saloon
staff()             // belongsTo Staff (nullable)
service()           // belongsTo Service
payment()           // hasOne Payment
review()            // hasOne Review
```

### Coupon Model
```php
// Methods
isValid()                   // Check if coupon is currently valid
calculateDiscount($amount)  // Calculate discount for given amount

// Validation
- Checks usage limits
- Validates date range
- Verifies minimum purchase amount
- Applies maximum discount cap
```

## Controllers

### Naming Convention
- SuperAdmin namespace: `App\Http\Controllers\SuperAdmin`
- SaloonAdmin namespace: `App\Http\Controllers\SaloonAdmin`
- User namespace: `App\Http\Controllers\User`
- Auth namespace: `App\Http\Controllers\Auth` (includes `SaloonRegistrationController`)

### Key Controllers

#### SuperAdmin\DashboardController
- **index()**: Shows system-wide statistics (Paid/Unpaid saloons), recent saloons, appointments, revenue charts

#### SaloonAdmin\DashboardController
- **index()**: Shows saloon-specific stats, today's appointments, top services

#### User\HomeController
- **index()**: Landing page with subscription-prioritized saloons and location-based filtering
- **saloons()**: Browse all saloons with search/filter (State, City, and Keywords)
- **saloonDetail($slug)**: View saloon details, services, staff, reviews
- **dashboard()**: User dashboard with status tracking

## Frontend Design

### Layout System

**Main Layout**: `resources/views/layouts/dashboard.blade.php`
- Includes sidebar and topbar partials
- Contains global CSS styles
- Responsive design with mobile menu toggle

**Sidebar**: `resources/views/layouts/partials/sidebar.blade.php`
- Role-based menu items
- Active state highlighting
- Logout button

**Topbar**: `resources/views/layouts/partials/topbar.blade.php`
- Page title and subtitle
- Notification bell
- User profile dropdown
- Home page navbar includes dedicated "Saloon Login" and "Saloon Registration" buttons for B2B growth.

### Design System

**Colors**:
```css
--primary: #6366f1 (Indigo)
--secondary: #8b5cf6 (Purple)
--success: #10b981 (Green)
--danger: #ef4444 (Red)
--warning: #f59e0b (Amber)
--info: #3b82f6 (Blue)
```

**Components**:
- `.stat-card`: Statistics card with hover effect
- `.stat-icon`: Gradient icon container
- `.btn-primary`: Primary action button
- `.table-container`: Styled table wrapper
- `.badge-*`: Status badges (success, danger, warning, info)
- `.form-control`: Styled form inputs

### Key Views

#### Super Admin
- `resources/views/superadmin/dashboard.blade.php`
  - Statistics grid (saloons, users, appointments, revenue)
  - Recent saloons list
  - Recent appointments list
  - Quick actions

#### Saloon Admin
- `resources/views/saloon-admin/dashboard.blade.php`
  - Saloon info banner
  - Stats (services, staff, appointments, revenue)
  - Today's appointments table
  - Top services
  - Recent appointments

#### User/Customer
- `resources/views/user/home.blade.php`
  - Hero section with gradient background
  - Service categories
  - Featured saloons grid
  - Features section
  - Footer

### Business Visibility & Subscription Logic

**Temporary Off System**:
- Saloons without an active/paid subscription are automatically hidden from the public home page and listing.
- Expired saloons show a warning in the Saloon Admin dashboard.
- Customers attempting to visit a direct link of an unpaid saloon see a "Temporarily Closed" message.

**Location Verification**:
- User must select a **State** and then a **City** (or use the combined selection) to find local stylists.
- The system prevents ghost listings by requiring location data on registration.

### DatabaseSeeder
Creates three default users:
1. Super Admin (admin@saloon.com)
2. Saloon Admin (saloon@saloon.com)
3. Customer (user@saloon.com)

All passwords: `password`

### CategorySeeder
Creates 6 default categories:
1. Hair Services
2. Beard & Shave
3. Skin Care
4. Spa & Massage
5. Makeup
6. Nail Care

## API Integration Points (Future)

### Payment Gateway
- Razorpay integration ready in payments table
- Fields: razorpay_payment_id, razorpay_order_id

### SMS Notifications
- Can integrate with Twilio/MSG91
- Appointment confirmations
- Status updates

### Email Notifications
- Laravel mail system ready
- Configure SMTP in .env

## Security Features

### CSRF Protection
- All forms include `@csrf` token
- Automatic validation on POST/PUT/DELETE requests

### SQL Injection Prevention
- Using Eloquent ORM exclusively
- Prepared statements for all queries

### XSS Protection
- Blade templates auto-escape output
- Use `{!! !!}` only for trusted HTML

### Password Security
- Bcrypt hashing (12 rounds)
- Automatic hashing via User model cast

### Role-Based Access Control
- Middleware validation on every protected route
- Active user check
- Role verification

## Performance Optimizations

### Eager Loading
```php
// Example in controllers
Appointment::with(['user', 'saloon', 'service', 'staff'])
```

### Database Indexes
- Primary keys on all tables
- Foreign key indexes
- Unique indexes on slug fields
- Composite unique indexes where needed

### Caching Strategy
- Config caching: `php artisan config:cache`
- Route caching: `php artisan route:cache`
- View caching: `php artisan view:cache`

## Testing Guidelines

### Manual Testing Scenarios

1. **Authentication Flow**
   - Register new user
   - Login with each role
   - Verify correct dashboard redirect
   - Test logout

2. **Super Admin**
   - Create/edit/delete saloon
   - Verify saloon
   - Toggle saloon status
   - Manage users

3. **Saloon Admin**
   - Add services
   - Add staff members
   - Assign services to staff
   - View/manage appointments

4. **User/Customer**
   - Browse saloons
   - View saloon details
   - Book appointment
   - View appointments
   - Cancel appointment

### Edge Cases to Test
- Inactive user login attempt
- Booking past dates
- Exceeding coupon usage limit
- Staff assigned to inactive service

## Deployment Checklist

### Pre-Deployment
- [ ] Set APP_ENV=production
- [ ] Set APP_DEBUG=false
- [ ] Generate new APP_KEY
- [ ] Configure production database
- [ ] Set up proper MAIL settings
- [ ] Review .gitignore file

### Server Setup
- [ ] PHP 8.2+ installed
- [ ] Required PHP extensions (pdo, mbstring, etc.)
- [ ] Composer installed globally
- [ ] Node.js and NPM installed
- [ ] MySQL/MariaDB configured

### Optimization
- [ ] Run `composer install --optimize-autoloader --no-dev`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Run `npm run build`

### Security
- [ ] Set proper file permissions (755 for directories, 644 for files)
- [ ] Storage and cache folders writable
- [ ] Configure firewall
- [ ] Set up SSL certificate
- [ ] Configure backups

### Monitoring
- [ ] Set up error logging
- [ ] Configure application monitoring
- [ ] Database backup schedule
- [ ] Uptime monitoring

## Maintenance

### Regular Tasks
- Database backups (daily recommended)
- Log rotation and cleanup
- Clear expired sessions
- Review and approve pending reviews
- Monitor disk space

### Updates
```bash
# Update dependencies
composer update
npm update

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## File Permissions

### Linux/Mac
```bash
# Set ownership
chown -R www-data:www-data .

# Set directory permissions
find . -type d -exec chmod 755 {} \;

# Set file permissions
find . -type f -exec chmod 644 {} \;

# Storage and cache need write access
chmod -R 775 storage bootstrap/cache
```

### Windows
- Ensure IIS_IUSRS or NETWORK SERVICE has write access to `storage` and `bootstrap/cache`

## Troubleshooting

### Common Issues

**Issue**: 403 Forbidden on routes
- **Solution**: Check RoleMiddleware, ensure user role matches route requirement

**Issue**: Images not uploading
- **Solution**: Check storage/app/public is linked, run `php artisan storage:link`

**Issue**: Migrations fail
- **Solution**: Check database connection, ensure database exists

**Issue**: Styles not loading
- **Solution**: Run `npm run build`, clear browser cache

**Issue**: Slow performance
- **Solution**: Enable query caching, use eager loading, optimize database queries

## Version History

- Initial release
- Three-panel system (Super Admin, Saloon Admin, User)
- Complete CRUD operations
- Advanced dashboard with statistics
- Modern UI with gradient designs
- Role-based authentication
- **v1.1.0 Enhancements**:
  - Separate Super Admin URL structure (`/super-admin`).
  - Integrated State & City filtering logic for saloon discovery.
  - Dedicated Saloon Partner onboarding flow (Business Registration).
  - "Temporary Off" payment enforcement system.
  - Paid vs Unpaid analytics for platform owners.

## Credits & Technologies

- **Framework**: Laravel 12.47.0
- **Authentication**: Laravel Breeze
- **Frontend**: Blade Templates, TailwindCSS
- **Icons**: Font Awesome 6.5.1
- **Fonts**: Inter (Google Fonts)
- **Build Tool**: Vite

---

**Developed with Laravel 12**  
**For Production Use**
