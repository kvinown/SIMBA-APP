<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Ganti dari Model ke Authenticatable
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lecturer extends Authenticatable // Ganti Model ke Authenticatable
{
    use HasFactory, Notifiable; // Tambahkan Notifiable untuk fitur notifikasi

    protected $table = 'lecturer'; // Pastikan nama tabel sesuai dengan database

    protected $fillable = [
        'nik', // Gunakan primary key yang sesuai
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

    protected $primaryKey = 'nik'; // Pastikan primary key sesuai

    public $incrementing = false; // Jika `nik` bukan auto-increment

    protected $keyType = 'string'; // Jika `nik` berupa string

    protected $hidden = [
        'password',
        'remember_token', // Pastikan ada jika menggunakan fitur remember me
    ];

    protected $casts = [
        'email_verified_at' => 'datetime', // Jika ada fitur email verification
        'password' => 'hashed', // Laravel 10+ mendukung hashing otomatis
    ];

    // Relasi ke Role
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    // Relasi ke Department
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    // Relasi ke Schedule
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'lecturer_nik', 'nik');
    }
}
