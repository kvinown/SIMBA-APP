<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedule';

    protected $primaryKey = null; // Karena menggunakan composite primary key, tidak ada satu kolom sebagai primary key.
    public $incrementing = false; // Menonaktifkan auto-increment pada primary key.

    protected $fillable = [
        'course_id',
        'lecturer_nik',
        'academic_period_id',
        'course_class',
        'type',
        'room_id',
        'additional_info',
        'class_day',
        'start_time',
        'end_time',
        'exam',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;

    public function course() : BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function lecturer() : BelongsTo
    {
        return $this->belongsTo(Lecturer::class, 'lecturer_nik', 'nik');
    }

    public function academicPeriod() : BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class, 'academic_period_id', 'id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

}
