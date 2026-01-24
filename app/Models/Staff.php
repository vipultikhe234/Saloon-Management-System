<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'saloon_id',
        'name',
        'email',
        'phone',
        'gender',
        'date_of_birth',
        'address',
        'photo',
        'specialization',
        'experience_years',
        'rating',
        'total_reviews',
        'commission_percentage',
        'is_active',
        'working_days',
        'shift_start',
        'shift_end',
    ];

    protected $casts = [
        'working_days' => 'array',
        'is_active' => 'boolean',
        'rating' => 'decimal:2',
        'commission_percentage' => 'decimal:2',
        'date_of_birth' => 'date',
    ];

    public function saloon()
    {
        return $this->belongsTo(Saloon::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'staff_services');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
