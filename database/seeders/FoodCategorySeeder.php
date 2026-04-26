<?php

namespace Database\Seeders;

use App\Models\FoodCategory;
use Illuminate\Database\Seeder;

class FoodCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'الحبوب والنشويات',
            'الخضروات',
            'الفواكه',
            'الأطعمة البروتينية',
            'منتجات الألبان وبدائلها',
            'الزيوت والدهون',
            'المشروبات',
            'الوجبات الخفيفة والحلويات',
            'البقوليات',
            'المكسرات والبذور',
            'الأعشاب والتوابل',
            'الشوربات والصلصات',
        ];

        foreach ($categories as $category) {
            FoodCategory::firstOrCreate(['name' => $category]);
        }
    }
}