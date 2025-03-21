<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lecturer extends Model
{
    use HasFactory;

    protected $table = 'lecturer';

    protected $fillable = [
        'nidn',
        'name',
        'email',
        'password',
        'status',
        'department_id',
        'role_id',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;

    protected $primaryKey = 'nik';

    public function role() : BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
    public function department() : BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'lecturer_nik', 'nik');
    }
}
