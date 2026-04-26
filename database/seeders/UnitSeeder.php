<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['name' => 'جم'],           // جرام
            ['name' => 'مل'],           // ملليلتر
            ['name' => 'قطعة'],         // قطعة كاملة (مثال: تفاح، بيض)
            ['name' => 'شريحة'],        // شريحة خبز، جبن
            ['name' => 'كوب'],          // 240 مل
            ['name' => 'ملعقة طعام'],    // ملعقة كبيرة (~15 مل)
            ['name' => 'ملعقة شاي'],     // ملعقة صغيرة (~5 مل)
            ['name' => 'أوقية'],         // أوقية (وزن)
            ['name' => 'كبير'],          // وصف الحجم (مثال: بيضة كبيرة)
            ['name' => 'متوسط'],         // وصف الحجم
            ['name' => 'صغير'],          // وصف الحجم
        ];

        foreach ($units as $unit) {
            Unit::firstOrCreate(['name' => $unit['name']]);
        }
    }
}