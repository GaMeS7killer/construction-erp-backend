<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaborType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'pay_type',
        'rate',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function projectLabor(): HasMany
    {
        return $this->hasMany(ProjectLabor::class);
    }
}
