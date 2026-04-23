<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use SoftDeletes;

    protected $table = 'equipment';

    protected $fillable = [
        'name',
        'unit',
        'rate_per_unit',
        'description',
    ];

    protected $casts = [
        'rate_per_unit' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function projectEquipment(): HasMany
    {
        return $this->hasMany(ProjectEquipment::class);
    }
}
