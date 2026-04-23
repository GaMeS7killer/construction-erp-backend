<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $equipmentItems = [
            ['name' => 'حفار صغير', 'unit' => 'day', 'rate_per_unit' => 180],
            ['name' => 'حفار كبير', 'unit' => 'day', 'rate_per_unit' => 260],
            ['name' => 'خلاطة خرسانة', 'unit' => 'day', 'rate_per_unit' => 75],
            ['name' => 'مضخة خرسانة', 'unit' => 'trip', 'rate_per_unit' => 140],
            ['name' => 'شاحنة نقل', 'unit' => 'trip', 'rate_per_unit' => 95],
            ['name' => 'رافعة برجية', 'unit' => 'day', 'rate_per_unit' => 320],
            ['name' => 'ونش متحرك', 'unit' => 'hour', 'rate_per_unit' => 45],
            ['name' => 'مولد كهرباء', 'unit' => 'hour', 'rate_per_unit' => 18],
            ['name' => 'ضاغط هواء', 'unit' => 'hour', 'rate_per_unit' => 15],
            ['name' => 'قاطع بلاط', 'unit' => 'day', 'rate_per_unit' => 22],
            ['name' => 'سقالة معدنية', 'unit' => 'day', 'rate_per_unit' => 30],
            ['name' => 'هزاز خرسانة', 'unit' => 'hour', 'rate_per_unit' => 9],
            ['name' => 'آلة لحام', 'unit' => 'hour', 'rate_per_unit' => 12],
            ['name' => 'معدة رش دهان', 'unit' => 'day', 'rate_per_unit' => 27],
            ['name' => 'لودر', 'unit' => 'day', 'rate_per_unit' => 210],
        ];

        Equipment::query()->insert(array_map(fn (array $item): array => [
            ...$item,
            'description' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ], $equipmentItems));
    }
}
