<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'saloon_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'discounted_price',
        'duration_minutes',
        'image',
        'images',
        'is_active',
        'is_featured',
        'gender',
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'discounted_price' => 'decimal:2',
    ];

    public function saloon()
    {
        return $this->belongsTo(Saloon::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function staff()
    {
        return $this->belongsToMany(Staff::class, 'staff_services');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function getFinalPriceAttribute()
    {
        return $this->discounted_price ?? $this->price;
    }
}
