<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $table = 'role';

    protected $fillable = [
        'name',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;

    public function lecturers() : HasMany
    {
        return $this->hasMany(Lecturer::class, 'role_id', 'id');
    }
}

