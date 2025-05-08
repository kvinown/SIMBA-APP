<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleDetail extends Model
{
    use HasFactory;

    protected $table = 'schedule_detail';

    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'week_num',
        'course_id',
        'lecturer_nik',
        'academic_period_id',
        'course_class',
        'type',
        'schedule_date',
        'topic',
        'course_start_time',
        'course_end_time',
        'student_count',
        'class_information',
        'checked',
        'file_path',
        'confirmed_date',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;

    /**
     * Relasi ke model Schedule
     */
    public function getScheduleAttribute()
    {
        return Schedule::where('course_id', $this->course_id)
            ->where('lecturer_nik', $this->lecturer_nik)
            ->where('academic_period_id', $this->academic_period_id)
            ->where('course_class', $this->course_class)
            ->where('type', $this->type)
            ->first();
    }
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
