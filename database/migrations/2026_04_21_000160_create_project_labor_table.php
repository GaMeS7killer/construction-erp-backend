<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_labor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phase_id')->constrained('project_phases')->cascadeOnDelete();
            $table->foreignId('labor_type_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('workers_count')->default(1);
            $table->unsignedInteger('days')->default(1);
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_labor');
    }
};
