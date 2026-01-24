<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Hair Services',
                'description' => 'All types of hair cutting, styling, and treatments',
                'icon' => '✂️',
            ],
            [
                'name' => 'Beard & Shave',
                'description' => 'Professional beard trimming and shaving services',
                'icon' => '🪒',
            ],
            [
                'name' => 'Skin Care',
                'description' => 'Facial treatments and skin care services',
                'icon' => '🧴',
            ],
            [
                'name' => 'Spa & Massage',
                'description' => 'Relaxing spa and massage treatments',
                'icon' => '💆',
            ],
            [
                'name' => 'Makeup',
                'description' => 'Professional makeup services for all occasions',
                'icon' => '💄',
            ],
            [
                'name' => 'Nail Care',
                'description' => 'Manicure, pedicure, and nail art services',
                'icon' => '💅',
            ],
        ];

        foreach ($categories as $index => $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'icon' => $category['icon'],
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
