<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $table = 'department';

    protected $fillable = [
        'name',
        'faculty_id',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;

    public function lecturers() : HasMany
    {
        return $this->hasMany(Lecturer::class, 'department_id', 'id');
    }
    public function faculty() : BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'faculty_id', 'id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'academic_period_id', 'id');
    }
}
