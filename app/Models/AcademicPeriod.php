<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicPeriod extends Model
{
    use HasFactory;

    protected $table = 'academic_period';

    protected $fillable = [
        'name',
        'active',
        'created_at',
        'updated_at',
    ];

    public $timestamps = true;

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'academic_period_id', 'id');
    }
}
