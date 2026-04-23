<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Equipment;
use App\Models\LaborType;
use App\Models\Material;
use App\Models\Project;
use App\Models\ProjectEquipment;
use App\Models\ProjectLabor;
use App\Models\ProjectMaterial;
use App\Models\ProjectPhase;
use App\Models\Quotation;
use Illuminate\Database\Seeder;

class DemoProjectSeeder extends Seeder
{
    public function run(): void
    {
        $client = Client::query()->create([
            'name' => 'شركة الإعمار الحديثة',
            'phone' => '0500000001',
            'email' => 'demo@construction.local',
            'address' => 'الرياض - حي الياسمين',
        ]);

        $project = Project::query()->create([
            'client_id' => $client->id,
            'name' => 'فيلا سكنية',
            'type' => 'house',
            'area_m2' => 420,
            'status' => 'in_progress',
            'start_date' => now()->subDays(20)->toDateString(),
            'notes' => 'مشروع تجريبي لنظام ERP الإنشائي',
        ]);

        $phaseNames = ['أعمال الموقع', 'الأساسات', 'الهيكل الخرساني', 'المباني', 'التشطيبات', 'الأعمال الكهروميكانيكية'];
        $materials = Material::query()->limit(12)->get();
        $laborTypes = LaborType::query()->limit(8)->get();
        $equipment = Equipment::query()->limit(6)->get();

        foreach ($phaseNames as $index => $phaseName) {
            $phase = ProjectPhase::query()->create([
                'project_id' => $project->id,
                'name' => $phaseName,
                'order_num' => $index + 1,
                'status' => $index < 3 ? 'completed' : 'in_progress',
                'start_date' => now()->subDays(18 - ($index * 2))->toDateString(),
                'notes' => 'مرحلة تجريبية رقم '.($index + 1),
            ]);

            foreach ($materials->slice($index, 2) as $material) {
                ProjectMaterial::query()->create([
                    'phase_id' => $phase->id,
                    'material_id' => $material->id,
                    'qty_estimated' => rand(50, 200),
                    'qty_actual' => rand(40, 180),
                    'unit_price' => $material->unit_price,
                ]);
            }

            foreach ($laborTypes->slice($index, 2) as $laborType) {
                ProjectLabor::query()->create([
                    'phase_id' => $phase->id,
                    'labor_type_id' => $laborType->id,
                    'workers_count' => rand(2, 6),
                    'days' => rand(5, 15),
                    'notes' => 'تكلفة عمالة للمرحلة '.$phaseName,
                ]);
            }

            foreach ($equipment->slice($index, 1) as $eq) {
                ProjectEquipment::query()->create([
                    'phase_id' => $phase->id,
                    'equipment_id' => $eq->id,
                    'qty_used' => rand(4, 12),
                    'notes' => 'استخدام معدة في '.$phaseName,
                ]);
            }
        }

        Quotation::generateFromProject($project, [
            'overhead_pct' => 10,
            'profit_pct' => 15,
            'contingency_pct' => 5,
            'notes' => 'عرض سعر تلقائي للمشروع التجريبي',
        ]);
    }
}
