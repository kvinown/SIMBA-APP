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
    ];

    public function lecturers() : HasMany
    {
        return $this->hasMany(Lecturer::class, 'department_id');
    }
    public function faculty() : BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }
}
