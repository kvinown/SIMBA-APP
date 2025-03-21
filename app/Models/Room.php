<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $table = 'room';

    protected $fillable = [
        'name',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'room_id', 'id');
    }
}
