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
    ];

    protected $primaryKey = 'nik';

    public function role() : BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
