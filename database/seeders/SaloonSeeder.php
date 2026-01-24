<?php

namespace Database\Seeders;

use App\Models\Saloon;
use App\Models\User;
use App\Models\Category;
use App\Models\Service;
use App\Models\Staff;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Coupon;
use App\Models\Notification;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SaloonSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('email', 'saloon@saloon.com')->first();
        $customer = User::where('email', 'user@saloon.com')->first();
        $category = Category::where('name', 'Hair Services')->first() ?: Category::first();
        $skinCategory = Category::where('name', 'Skin Care')->first() ?: Category::first();

        if ($owner && $customer && $category) {
            // 1. Create Saloon
            $saloon = Saloon::create([
                'owner_id' => $owner->id,
                'name' => 'Premium Style Studio',
                'slug' => 'premium-style-studio',
                'description' => 'Your one-stop destination for premium styling and grooming.',
                'email' => 'studio@premiumstyle.com',
                'phone' => '9888877777',
                'address' => '123 Fashion Street, HSR Layout',
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'zip_code' => '560102',
                'opening_time' => '10:00:00',
                'closing_time' => '21:00:00',
                'working_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                'is_active' => true,
                'is_verified' => true,
                'subscription_level' => 'platinum',
                'subscription_expires_at' => now()->addMonth(),
                'rating' => 4.9,
                'total_reviews' => 2,
            ]);

            // 2. Create Services
            $haircut = Service::create([
                'saloon_id' => $saloon->id,
                'category_id' => $category->id,
                'name' => 'Signature Haircut',
                'slug' => 'signature-haircut',
                'description' => 'Our best-selling hair cutting service with styling.',
                'price' => 500.00,
                'duration_minutes' => 45,
                'is_active' => true,
                'is_featured' => true,
            ]);

            $facial = Service::create([
                'saloon_id' => $saloon->id,
                'category_id' => $skinCategory->id,
                'name' => 'Gold Facial',
                'slug' => 'gold-facial',
                'description' => 'Premium skin rejuvenation facial.',
                'price' => 1200.00,
                'duration_minutes' => 60,
                'is_active' => true,
                'is_featured' => true,
            ]);

            $beardTrim = Service::create([
                'saloon_id' => $saloon->id,
                'category_id' => Category::where('name', 'Beard & Shave')->first()->id ?? $category->id,
                'name' => 'Royal Beard Trim',
                'slug' => 'royal-beard-trim',
                'description' => 'Classic beard styling and grooming.',
                'price' => 300.00,
                'duration_minutes' => 30,
                'is_active' => true,
            ]);

            // 3. Create Staff
            $mike = Staff::create([
                'saloon_id' => $saloon->id,
                'name' => 'Expert Mike',
                'email' => 'mike@premiumstyle.com',
                'phone' => '9999988888',
                'specialization' => 'Master Barber',
                'rating' => 5.0,
                'is_active' => true,
            ]);

            $sarah = Staff::create([
                'saloon_id' => $saloon->id,
                'name' => 'Sarah Styles',
                'email' => 'sarah@premiumstyle.com',
                'phone' => '9999977777',
                'specialization' => 'Skin Specialist',
                'rating' => 4.8,
                'is_active' => true,
            ]);

            // 4. Assign Services to Staff
            $mike->services()->attach([$haircut->id, $beardTrim->id]);
            $sarah->services()->attach([$facial->id]);

            // 5. Create Coupon
            Coupon::create([
                'saloon_id' => $saloon->id,
                'code' => 'WELCOME100',
                'title' => 'Welcome Discount',
                'description' => 'Get 100 OFF on your first booking!',
                'discount_type' => 'fixed',
                'discount_value' => 100.00,
                'min_purchase_amount' => 400.00,
                'valid_from' => now(),
                'valid_until' => now()->addYear(),
                'is_active' => true,
            ]);

            // 6. Create Multiple Appointments (History & Today)
            // Past Appointment (Completed)
            $apt1 = Appointment::create([
                'appointment_number' => 'APT-' . strtoupper(Str::random(8)),
                'user_id' => $customer->id,
                'saloon_id' => $saloon->id,
                'staff_id' => $mike->id,
                'service_id' => $haircut->id,
                'appointment_date' => now()->subDays(2)->format('Y-m-d'),
                'appointment_time' => '11:00:00',
                'duration_minutes' => 45,
                'status' => 'completed',
                'total_amount' => 500.00,
                'discount_amount' => 0,
                'final_amount' => 500.00,
                'completed_at' => now()->subDays(2),
            ]);

            Payment::create([
                'appointment_id' => $apt1->id,
                'user_id' => $customer->id,
                'transaction_id' => 'TXN-' . Str::upper(Str::random(10)),
                'amount' => 500.00,
                'payment_method' => 'cash',
                'status' => 'completed',
                'paid_at' => now()->subDays(2),
            ]);

            Review::create([
                'user_id' => $customer->id,
                'saloon_id' => $saloon->id,
                'appointment_id' => $apt1->id,
                'staff_id' => $mike->id,
                'rating' => 5,
                'comment' => 'Mike is the best! Great haircut.',
                'is_approved' => true,
            ]);

            // Today's Appointment (Confirmed)
            Appointment::create([
                'appointment_number' => 'APT-' . strtoupper(Str::random(8)),
                'user_id' => $customer->id,
                'saloon_id' => $saloon->id,
                'staff_id' => $sarah->id,
                'service_id' => $facial->id,
                'appointment_date' => now()->format('Y-m-d'),
                'appointment_time' => '14:30:00',
                'duration_minutes' => 60,
                'status' => 'confirmed',
                'total_amount' => 1200.00,
                'discount_amount' => 100.00,
                'final_amount' => 1100.00,
            ]);

            // 7. Create Notifications
            Notification::create([
                'user_id' => $customer->id,
                'title' => 'Appointment Confirmed',
                'message' => 'Your appointment for Gold Facial is confirmed for today at 2:30 PM.',
                'type' => 'appointment',
                'is_read' => false,
            ]);

            // 8. Create Settings
            $settings = [
                ['key' => 'site_name', 'value' => 'Saloon Manager', 'group' => 'general'],
                ['key' => 'contact_email', 'value' => 'info@saloonmanager.com', 'group' => 'general'],
                ['key' => 'currency_symbol', 'value' => '₹', 'group' => 'localization'],
            ];

            foreach ($settings as $setting) {
                Setting::updateOrCreate(['key' => $setting['key']], $setting);
            }
        }
    }
}
