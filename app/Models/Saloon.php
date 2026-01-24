<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Saloon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'description',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'latitude',
        'longitude',
        'logo',
        'images',
        'opening_time',
        'closing_time',
        'working_days',
        'is_active',
        'is_verified',
        'subscription_level',
        'subscription_expires_at',
        'stripe_id',
        'rating',
        'total_reviews',
        'avg_wait_time_per_customer',
    ];

    public function getQueueStats()
    {
        $today = now()->toDateString();
        $waitingCount = $this->appointments()
            ->whereDate('appointment_date', $today)
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->count();
        
        $waitTime = $waitingCount * ($this->avg_wait_time_per_customer ?: 15);
        $expectedTime = now()->addMinutes($waitTime);

        return [
            'waiting_count' => $waitingCount,
            'wait_time_minutes' => $waitTime,
            'expected_reach_time' => $expectedTime->format('h:i A'),
        ];
    }

    protected $casts = [
        'images' => 'array',
        'working_days' => 'array',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'subscription_expires_at' => 'datetime',
        'rating' => 'decimal:2',
    ];

    public function isSubscriptionActive()
    {
        if (!$this->subscription_expires_at) {
            return false;
        }
        return $this->subscription_expires_at->isFuture();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($saloon) {
            if (empty($saloon->slug)) {
                $saloon->slug = Str::slug($saloon->name);
            }
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }
}
