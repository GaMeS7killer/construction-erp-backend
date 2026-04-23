<?php

namespace Database\Seeders;

use App\Models\LaborType;
use Illuminate\Database\Seeder;

class LaborTypesSeeder extends Seeder
{
    public function run(): void
    {
        $laborTypes = [
            ['title' => 'عامل بناء', 'pay_type' => 'daily', 'rate' => 20],
            ['title' => 'نجار مسلح', 'pay_type' => 'daily', 'rate' => 28],
            ['title' => 'حداد تسليح', 'pay_type' => 'daily', 'rate' => 30],
            ['title' => 'صبّاب خرسانة', 'pay_type' => 'daily', 'rate' => 26],
            ['title' => 'مبلط', 'pay_type' => 'daily', 'rate' => 25],
            ['title' => 'دهان', 'pay_type' => 'daily', 'rate' => 24],
            ['title' => 'سباك', 'pay_type' => 'daily', 'rate' => 32],
            ['title' => 'كهربائي', 'pay_type' => 'daily', 'rate' => 34],
            ['title' => 'فني تكييف', 'pay_type' => 'daily', 'rate' => 36],
            ['title' => 'مشرف موقع', 'pay_type' => 'monthly', 'rate' => 1200],
            ['title' => 'مهندس مدني', 'pay_type' => 'monthly', 'rate' => 1800],
            ['title' => 'مهندس معماري', 'pay_type' => 'monthly', 'rate' => 1700],
            ['title' => 'مسّاح', 'pay_type' => 'fixed', 'rate' => 450],
            ['title' => 'فني مختبر تربة', 'pay_type' => 'fixed', 'rate' => 380],
            ['title' => 'فريق تنظيف نهائي', 'pay_type' => 'fixed', 'rate' => 320],
            ['title' => 'فني عزل', 'pay_type' => 'daily', 'rate' => 29],
        ];

        LaborType::query()->insert(array_map(fn (array $item): array => [
            ...$item,
            'created_at' => now(),
            'updated_at' => now(),
        ], $laborTypes));
    }
}
