<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;

class MaterialsSeeder extends Seeder
{
    public function run(): void
    {
        $materials = [
            ['name' => 'أسمنت بورتلاندي', 'unit' => 'kg', 'unit_price' => 0.45, 'category' => 'concrete'],
            ['name' => 'رمل ناعم', 'unit' => 'm3', 'unit_price' => 18, 'category' => 'concrete'],
            ['name' => 'حصى خشن', 'unit' => 'm3', 'unit_price' => 22, 'category' => 'concrete'],
            ['name' => 'خرسانة جاهزة C25', 'unit' => 'm3', 'unit_price' => 78, 'category' => 'concrete'],
            ['name' => 'حديد تسليح 8 مم', 'unit' => 'ton', 'unit_price' => 620, 'category' => 'steel'],
            ['name' => 'حديد تسليح 12 مم', 'unit' => 'ton', 'unit_price' => 640, 'category' => 'steel'],
            ['name' => 'حديد تسليح 16 مم', 'unit' => 'ton', 'unit_price' => 660, 'category' => 'steel'],
            ['name' => 'شبك حديد ملحوم', 'unit' => 'm2', 'unit_price' => 6.8, 'category' => 'steel'],
            ['name' => 'طوب إسمنتي 20 سم', 'unit' => 'piece', 'unit_price' => 0.75, 'category' => 'block'],
            ['name' => 'طوب إسمنتي 15 سم', 'unit' => 'piece', 'unit_price' => 0.62, 'category' => 'block'],
            ['name' => 'بلوك عازل حراري', 'unit' => 'piece', 'unit_price' => 1.1, 'category' => 'block'],
            ['name' => 'دهان داخلي مطفي', 'unit' => 'liter', 'unit_price' => 3.6, 'category' => 'paint'],
            ['name' => 'دهان خارجي مقاوم', 'unit' => 'liter', 'unit_price' => 4.9, 'category' => 'paint'],
            ['name' => 'برايمر أكريليك', 'unit' => 'liter', 'unit_price' => 2.7, 'category' => 'paint'],
            ['name' => 'بلاط سيراميك أرضيات', 'unit' => 'm2', 'unit_price' => 12, 'category' => 'tile'],
            ['name' => 'بلاط بورسلان', 'unit' => 'm2', 'unit_price' => 18, 'category' => 'tile'],
            ['name' => 'بلاط جدران حمامات', 'unit' => 'm2', 'unit_price' => 14, 'category' => 'tile'],
            ['name' => 'غراء بلاط', 'unit' => 'kg', 'unit_price' => 0.55, 'category' => 'tile'],
            ['name' => 'فواصل بلاط', 'unit' => 'kg', 'unit_price' => 1.4, 'category' => 'tile'],
            ['name' => 'ألواح صوف صخري', 'unit' => 'm2', 'unit_price' => 8.3, 'category' => 'insulation'],
            ['name' => 'ألواح بوليسترين', 'unit' => 'm2', 'unit_price' => 6.1, 'category' => 'insulation'],
            ['name' => 'رول عزل مائي', 'unit' => 'm2', 'unit_price' => 4.2, 'category' => 'insulation'],
            ['name' => 'بيتومين سائل', 'unit' => 'liter', 'unit_price' => 2.2, 'category' => 'insulation'],
            ['name' => 'أنابيب PVC 4 بوصة', 'unit' => 'meter', 'unit_price' => 2.8, 'category' => 'other'],
            ['name' => 'أنابيب PPR للمياه', 'unit' => 'meter', 'unit_price' => 1.9, 'category' => 'other'],
            ['name' => 'كابل كهرباء 6 مم', 'unit' => 'meter', 'unit_price' => 1.6, 'category' => 'other'],
            ['name' => 'لوح جبس بورد', 'unit' => 'piece', 'unit_price' => 7.5, 'category' => 'other'],
            ['name' => 'مسمار تثبيت معدني', 'unit' => 'piece', 'unit_price' => 0.08, 'category' => 'other'],
            ['name' => 'خشب شدات', 'unit' => 'm3', 'unit_price' => 210, 'category' => 'other'],
            ['name' => 'سلك ربط حديد', 'unit' => 'kg', 'unit_price' => 1.3, 'category' => 'steel'],
            ['name' => 'مادة مانعة للتسرب', 'unit' => 'liter', 'unit_price' => 5.4, 'category' => 'other'],
            ['name' => 'معجون جدران', 'unit' => 'kg', 'unit_price' => 0.9, 'category' => 'paint'],
        ];

        Material::query()->insert(array_map(function (array $item): array {
            return [
                ...$item,
                'description' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $materials));
    }
}
