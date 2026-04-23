<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->decimal('materials_total', 12, 2)->default(0);
            $table->decimal('labor_total', 12, 2)->default(0);
            $table->decimal('equipment_total', 12, 2)->default(0);
            $table->decimal('overhead_pct', 12, 2)->default(10);
            $table->decimal('profit_pct', 12, 2)->default(15);
            $table->decimal('contingency_pct', 12, 2)->default(5);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->enum('status', ['draft', 'sent', 'approved', 'rejected'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
