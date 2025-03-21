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
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, [
            'course_id',
            'lecturer_nik',
            'academic_period_id',
            'course_class',
            'type'
        ], [
            'course_id',
            'lecturer_nik',
            'academic_period_id',
            'course_class',
            'type'
        ]);
    }
}
