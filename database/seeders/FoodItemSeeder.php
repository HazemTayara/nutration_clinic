<?php

// database/seeders/FoodItemSeeder.php
namespace Database\Seeders;

use App\Models\FoodCategory;
use App\Models\FoodItem;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoodItemSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure units and categories exist
        $unitGram = Unit::where('name', 'g')->firstOrFail();
        $unitMl = Unit::where('name', 'ml')->firstOrFail();
        $unitPiece = Unit::where('name', 'piece')->firstOrFail();
        $unitSlice = Unit::where('name', 'slice')->firstOrFail();
        $unitCup = Unit::where('name', 'cup')->firstOrFail();
        $unitTbsp = Unit::where('name', 'tbsp')->firstOrFail();
        $unitTsp = Unit::where('name', 'tsp')->firstOrFail();
        $unitOz = Unit::where('name', 'oz')->firstOrFail();
        $unitLarge = Unit::where('name', 'large')->firstOrFail();
        $unitMedium = Unit::where('name', 'medium')->firstOrFail();
        $unitSmall = Unit::where('name', 'small')->firstOrFail();

        $categories = FoodCategory::pluck('id', 'name')->all();

        $foodItems = [
            // الحبوب والنشويات
            ['name' => 'خبز أبيض', 'category' => 'Grains & Cereals', 'calories' => 79, 'carbs' => 14.8, 'protein' => 2.7, 'fat' => 1.0, 'portion_quantity' => 1, 'unit' => $unitSlice],
            ['name' => 'خبز قمح كامل', 'category' => 'Grains & Cereals', 'calories' => 81, 'carbs' => 13.8, 'protein' => 4.0, 'fat' => 1.1, 'portion_quantity' => 1, 'unit' => $unitSlice],
            ['name' => 'خبز الجاودار', 'category' => 'Grains & Cereals', 'calories' => 83, 'carbs' => 15.5, 'protein' => 2.7, 'fat' => 1.1, 'portion_quantity' => 1, 'unit' => $unitSlice],
            ['name' => 'خبز بيتا', 'category' => 'Grains & Cereals', 'calories' => 165, 'carbs' => 33.0, 'protein' => 5.5, 'fat' => 0.7, 'portion_quantity' => 1, 'unit' => $unitPiece],
            ['name' => 'تورتيلا (ذرة)', 'category' => 'Grains & Cereals', 'calories' => 52, 'carbs' => 10.7, 'protein' => 1.4, 'fat' => 0.7, 'portion_quantity' => 1, 'unit' => $unitPiece],
            ['name' => 'تورتيلا (دقيق)', 'category' => 'Grains & Cereals', 'calories' => 94, 'carbs' => 15.6, 'protein' => 2.5, 'fat' => 2.5, 'portion_quantity' => 1, 'unit' => $unitPiece],
            ['name' => 'أرز أبيض (مطبوخ)', 'category' => 'Grains & Cereals', 'calories' => 130, 'carbs' => 28.2, 'protein' => 2.7, 'fat' => 0.3, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'أرز بني (مطبوخ)', 'category' => 'Grains & Cereals', 'calories' => 123, 'carbs' => 25.6, 'protein' => 2.7, 'fat' => 1.0, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'كينوا (مطبوخ)', 'category' => 'Grains & Cereals', 'calories' => 120, 'carbs' => 21.3, 'protein' => 4.4, 'fat' => 1.9, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'شوفان (جاف)', 'category' => 'Grains & Cereals', 'calories' => 389, 'carbs' => 66.3, 'protein' => 16.9, 'fat' => 6.9, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'مكرونة (مطبوخة)', 'category' => 'Grains & Cereals', 'calories' => 158, 'carbs' => 30.9, 'protein' => 5.8, 'fat' => 0.9, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'كسكسي (مطبوخ)', 'category' => 'Grains & Cereals', 'calories' => 112, 'carbs' => 23.2, 'protein' => 3.8, 'fat' => 0.2, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'برغل (مطبوخ)', 'category' => 'Grains & Cereals', 'calories' => 83, 'carbs' => 18.6, 'protein' => 3.1, 'fat' => 0.2, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'شعير (مطبوخ)', 'category' => 'Grains & Cereals', 'calories' => 123, 'carbs' => 28.2, 'protein' => 2.3, 'fat' => 0.4, 'portion_quantity' => 100, 'unit' => $unitGram],

            // الخضروات
            ['name' => 'بروكلي (نيء)', 'category' => 'Vegetables', 'calories' => 34, 'carbs' => 6.6, 'protein' => 2.8, 'fat' => 0.4, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'سبانخ (نيء)', 'category' => 'Vegetables', 'calories' => 23, 'carbs' => 3.6, 'protein' => 2.9, 'fat' => 0.4, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'كرنب أجعد (نيء)', 'category' => 'Vegetables', 'calories' => 49, 'carbs' => 8.8, 'protein' => 4.3, 'fat' => 0.9, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'جزر (نيء)', 'category' => 'Vegetables', 'calories' => 41, 'carbs' => 9.6, 'protein' => 0.9, 'fat' => 0.2, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'جزر', 'category' => 'Vegetables', 'calories' => 25, 'carbs' => 5.8, 'protein' => 0.6, 'fat' => 0.1, 'portion_quantity' => 1, 'unit' => $unitMedium],
            ['name' => 'طماطم', 'category' => 'Vegetables', 'calories' => 22, 'carbs' => 4.8, 'protein' => 1.1, 'fat' => 0.2, 'portion_quantity' => 1, 'unit' => $unitMedium],
            ['name' => 'خيار', 'category' => 'Vegetables', 'calories' => 45, 'carbs' => 10.9, 'protein' => 2.0, 'fat' => 0.3, 'portion_quantity' => 1, 'unit' => $unitLarge],
            ['name' => 'فلفل رومي', 'category' => 'Vegetables', 'calories' => 31, 'carbs' => 6.0, 'protein' => 1.0, 'fat' => 0.3, 'portion_quantity' => 1, 'unit' => $unitMedium],
            ['name' => 'بصل', 'category' => 'Vegetables', 'calories' => 40, 'carbs' => 9.3, 'protein' => 1.1, 'fat' => 0.1, 'portion_quantity' => 1, 'unit' => $unitMedium],
            ['name' => 'ثوم (فص)', 'category' => 'Vegetables', 'calories' => 4, 'carbs' => 1.0, 'protein' => 0.2, 'fat' => 0.0, 'portion_quantity' => 1, 'unit' => $unitPiece],
            ['name' => 'كوسة', 'category' => 'Vegetables', 'calories' => 33, 'carbs' => 6.1, 'protein' => 2.4, 'fat' => 0.6, 'portion_quantity' => 1, 'unit' => $unitMedium],
            ['name' => 'باذنجان', 'category' => 'Vegetables', 'calories' => 25, 'carbs' => 5.9, 'protein' => 1.0, 'fat' => 0.2, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'قرنبيط', 'category' => 'Vegetables', 'calories' => 25, 'carbs' => 5.0, 'protein' => 1.9, 'fat' => 0.3, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'بطاطس', 'category' => 'Vegetables', 'calories' => 161, 'carbs' => 36.6, 'protein' => 4.3, 'fat' => 0.2, 'portion_quantity' => 1, 'unit' => $unitMedium],
            ['name' => 'بطاطا حلوة', 'category' => 'Vegetables', 'calories' => 103, 'carbs' => 23.6, 'protein' => 2.3, 'fat' => 0.2, 'portion_quantity' => 1, 'unit' => $unitMedium],
            ['name' => 'خس (آيسبرغ)', 'category' => 'Vegetables', 'calories' => 14, 'carbs' => 3.0, 'protein' => 0.9, 'fat' => 0.1, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'فطر (أبيض)', 'category' => 'Vegetables', 'calories' => 22, 'carbs' => 3.3, 'protein' => 3.1, 'fat' => 0.3, 'portion_quantity' => 100, 'unit' => $unitGram],

            // الفواكه
            ['name' => 'تفاح', 'category' => 'Fruits', 'calories' => 95, 'carbs' => 25.1, 'protein' => 0.5, 'fat' => 0.3, 'portion_quantity' => 1, 'unit' => $unitMedium],
            ['name' => 'موز', 'category' => 'Fruits', 'calories' => 105, 'carbs' => 27.0, 'protein' => 1.3, 'fat' => 0.4, 'portion_quantity' => 1, 'unit' => $unitMedium],
            ['name' => 'برتقال', 'category' => 'Fruits', 'calories' => 62, 'carbs' => 15.4, 'protein' => 1.2, 'fat' => 0.2, 'portion_quantity' => 1, 'unit' => $unitMedium],
            ['name' => 'فراولة', 'category' => 'Fruits', 'calories' => 32, 'carbs' => 7.7, 'protein' => 0.7, 'fat' => 0.3, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'توت أزرق', 'category' => 'Fruits', 'calories' => 57, 'carbs' => 14.5, 'protein' => 0.7, 'fat' => 0.3, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'عنب', 'category' => 'Fruits', 'calories' => 69, 'carbs' => 18.1, 'protein' => 0.7, 'fat' => 0.2, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'بطيخ', 'category' => 'Fruits', 'calories' => 30, 'carbs' => 7.6, 'protein' => 0.6, 'fat' => 0.2, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'أناناس', 'category' => 'Fruits', 'calories' => 50, 'carbs' => 13.1, 'protein' => 0.5, 'fat' => 0.1, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'مانجو', 'category' => 'Fruits', 'calories' => 202, 'carbs' => 50.3, 'protein' => 2.8, 'fat' => 1.3, 'portion_quantity' => 1, 'unit' => $unitPiece],
            ['name' => 'أفوكادو', 'category' => 'Fruits', 'calories' => 240, 'carbs' => 12.8, 'protein' => 3.0, 'fat' => 22.0, 'portion_quantity' => 1, 'unit' => $unitMedium],
            ['name' => 'كمثرى', 'category' => 'Fruits', 'calories' => 101, 'carbs' => 27.1, 'protein' => 0.6, 'fat' => 0.2, 'portion_quantity' => 1, 'unit' => $unitMedium],
            ['name' => 'خوخ', 'category' => 'Fruits', 'calories' => 58, 'carbs' => 14.0, 'protein' => 1.4, 'fat' => 0.4, 'portion_quantity' => 1, 'unit' => $unitMedium],
            ['name' => 'كيوي', 'category' => 'Fruits', 'calories' => 42, 'carbs' => 10.1, 'protein' => 0.8, 'fat' => 0.4, 'portion_quantity' => 1, 'unit' => $unitMedium],
            ['name' => 'تمر (مجول)', 'category' => 'Fruits', 'calories' => 66, 'carbs' => 18.0, 'protein' => 0.4, 'fat' => 0.0, 'portion_quantity' => 1, 'unit' => $unitPiece],
            ['name' => 'زبيب', 'category' => 'Fruits', 'calories' => 299, 'carbs' => 79.2, 'protein' => 3.1, 'fat' => 0.5, 'portion_quantity' => 100, 'unit' => $unitGram],

            // الأطعمة البروتينية
            ['name' => 'صدر دجاج (بدون جلد، نيء)', 'category' => 'Protein Foods', 'calories' => 120, 'carbs' => 0.0, 'protein' => 22.5, 'fat' => 2.6, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'ستيك لحم بقري (سيرلوين، نيء)', 'category' => 'Protein Foods', 'calories' => 205, 'carbs' => 0.0, 'protein' => 26.0, 'fat' => 10.0, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'لحم بقري مفروم (85% خالي من الدهون)', 'category' => 'Protein Foods', 'calories' => 250, 'carbs' => 0.0, 'protein' => 17.0, 'fat' => 20.0, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'شريحة لحم خنزير (نيء)', 'category' => 'Protein Foods', 'calories' => 242, 'carbs' => 0.0, 'protein' => 27.0, 'fat' => 14.0, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'سلمون (نيء)', 'category' => 'Protein Foods', 'calories' => 208, 'carbs' => 0.0, 'protein' => 20.4, 'fat' => 13.4, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'تونة (معلبة بالماء)', 'category' => 'Protein Foods', 'calories' => 116, 'carbs' => 0.0, 'protein' => 25.5, 'fat' => 0.8, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'بيض', 'category' => 'Protein Foods', 'calories' => 72, 'carbs' => 0.4, 'protein' => 6.3, 'fat' => 4.8, 'portion_quantity' => 1, 'unit' => $unitLarge],
            ['name' => 'توفو (صلب)', 'category' => 'Protein Foods', 'calories' => 144, 'carbs' => 2.8, 'protein' => 17.3, 'fat' => 8.7, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'تيمبيه', 'category' => 'Protein Foods', 'calories' => 193, 'carbs' => 9.4, 'protein' => 20.3, 'fat' => 10.8, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'عدس (مطبوخ)', 'category' => 'Protein Foods', 'calories' => 116, 'carbs' => 20.1, 'protein' => 9.0, 'fat' => 0.4, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'حمص (مطبوخ)', 'category' => 'Protein Foods', 'calories' => 139, 'carbs' => 22.5, 'protein' => 7.0, 'fat' => 4.2, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'فاصوليا سوداء (مطبوخة)', 'category' => 'Protein Foods', 'calories' => 132, 'carbs' => 23.7, 'protein' => 8.9, 'fat' => 0.5, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'جمبري (نيء)', 'category' => 'Protein Foods', 'calories' => 85, 'carbs' => 0.0, 'protein' => 20.0, 'fat' => 0.5, 'portion_quantity' => 100, 'unit' => $unitGram],

            // الألبان وبدائلها
            ['name' => 'حليب (كامل الدسم)', 'category' => 'Dairy & Alternatives', 'calories' => 61, 'carbs' => 4.8, 'protein' => 3.2, 'fat' => 3.3, 'portion_quantity' => 100, 'unit' => $unitMl],
            ['name' => 'حليب (منزوع الدسم)', 'category' => 'Dairy & Alternatives', 'calories' => 34, 'carbs' => 5.0, 'protein' => 3.4, 'fat' => 0.1, 'portion_quantity' => 100, 'unit' => $unitMl],
            ['name' => 'زبادي (يوناني، سادة)', 'category' => 'Dairy & Alternatives', 'calories' => 59, 'carbs' => 3.6, 'protein' => 10.0, 'fat' => 0.4, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'زبادي (عادي، سادة)', 'category' => 'Dairy & Alternatives', 'calories' => 61, 'carbs' => 7.0, 'protein' => 3.5, 'fat' => 3.3, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'جبن شيدر', 'category' => 'Dairy & Alternatives', 'calories' => 404, 'carbs' => 3.1, 'protein' => 22.9, 'fat' => 33.3, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'جبن موزاريلا', 'category' => 'Dairy & Alternatives', 'calories' => 280, 'carbs' => 3.1, 'protein' => 27.5, 'fat' => 17.1, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'جبن قريش (1% دسم)', 'category' => 'Dairy & Alternatives', 'calories' => 72, 'carbs' => 2.7, 'protein' => 12.4, 'fat' => 1.0, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'حليب صويا (غير محلى)', 'category' => 'Dairy & Alternatives', 'calories' => 33, 'carbs' => 1.7, 'protein' => 3.3, 'fat' => 1.7, 'portion_quantity' => 100, 'unit' => $unitMl],
            ['name' => 'حليب لوز (غير محلى)', 'category' => 'Dairy & Alternatives', 'calories' => 15, 'carbs' => 0.6, 'protein' => 0.6, 'fat' => 1.2, 'portion_quantity' => 100, 'unit' => $unitMl],

            // الدهون والزيوت
            ['name' => 'زيت زيتون', 'category' => 'Fats & Oils', 'calories' => 884, 'carbs' => 0.0, 'protein' => 0.0, 'fat' => 100.0, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'زبدة', 'category' => 'Fats & Oils', 'calories' => 717, 'carbs' => 0.1, 'protein' => 0.9, 'fat' => 81.1, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'زبدة', 'category' => 'Fats & Oils', 'calories' => 102, 'carbs' => 0.0, 'protein' => 0.1, 'fat' => 11.5, 'portion_quantity' => 1, 'unit' => $unitTbsp],
            ['name' => 'زيت جوز الهند', 'category' => 'Fats & Oils', 'calories' => 862, 'carbs' => 0.0, 'protein' => 0.0, 'fat' => 100.0, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'زبدة الفول السوداني', 'category' => 'Fats & Oils', 'calories' => 588, 'carbs' => 20.0, 'protein' => 25.0, 'fat' => 50.0, 'portion_quantity' => 100, 'unit' => $unitGram],

            // المكسرات والبذور
            ['name' => 'لوز', 'category' => 'Nuts & Seeds', 'calories' => 579, 'carbs' => 21.6, 'protein' => 21.2, 'fat' => 49.9, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'جوز (عين الجمل)', 'category' => 'Nuts & Seeds', 'calories' => 654, 'carbs' => 13.7, 'protein' => 15.2, 'fat' => 65.2, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'بذور الشيا', 'category' => 'Nuts & Seeds', 'calories' => 486, 'carbs' => 42.1, 'protein' => 16.5, 'fat' => 30.7, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'بذور الكتان', 'category' => 'Nuts & Seeds', 'calories' => 534, 'carbs' => 28.9, 'protein' => 18.3, 'fat' => 42.2, 'portion_quantity' => 100, 'unit' => $unitGram],

            // المشروبات
            ['name' => 'قهوة (سادة)', 'category' => 'Beverages', 'calories' => 1, 'carbs' => 0.0, 'protein' => 0.1, 'fat' => 0.0, 'portion_quantity' => 240, 'unit' => $unitMl],
            ['name' => 'شاي (أسود، بدون سكر)', 'category' => 'Beverages', 'calories' => 2, 'carbs' => 0.5, 'protein' => 0.0, 'fat' => 0.0, 'portion_quantity' => 240, 'unit' => $unitMl],
            ['name' => 'عصير برتقال', 'category' => 'Beverages', 'calories' => 112, 'carbs' => 25.8, 'protein' => 1.7, 'fat' => 0.5, 'portion_quantity' => 240, 'unit' => $unitMl],
            ['name' => 'عصير تفاح', 'category' => 'Beverages', 'calories' => 114, 'carbs' => 28.0, 'protein' => 0.3, 'fat' => 0.3, 'portion_quantity' => 240, 'unit' => $unitMl],

            // الوجبات الخفيفة والحلويات
            ['name' => 'شوكولاتة داكنة (70-85%)', 'category' => 'Snacks & Sweets', 'calories' => 598, 'carbs' => 45.9, 'protein' => 7.8, 'fat' => 42.6, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'بوشار (مفرقع بالهواء)', 'category' => 'Snacks & Sweets', 'calories' => 387, 'carbs' => 77.9, 'protein' => 12.9, 'fat' => 4.5, 'portion_quantity' => 100, 'unit' => $unitGram],
            ['name' => 'رقائق البطاطس', 'category' => 'Snacks & Sweets', 'calories' => 536, 'carbs' => 53.0, 'protein' => 7.0, 'fat' => 34.6, 'portion_quantity' => 100, 'unit' => $unitGram],
        ];

        foreach ($foodItems as $item) {
            FoodItem::firstOrCreate(
                ['name' => $item['name'], 'food_category_id' => $categories[$item['category']]],
                [
                    'calories' => $item['calories'],
                    'carbs' => $item['carbs'],
                    'protein' => $item['protein'],
                    'fat' => $item['fat'],
                    'portion_quantity' => $item['portion_quantity'],
                    'unit_id' => $item['unit']->id,
                ]
            );
        }
    }
}