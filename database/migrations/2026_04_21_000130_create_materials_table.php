<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('unit', ['m2', 'm3', 'ton', 'piece', 'liter', 'kg', 'meter']);
            $table->decimal('unit_price', 12, 2);
            $table->enum('category', ['concrete', 'steel', 'block', 'paint', 'tile', 'insulation', 'other'])->default('other');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
