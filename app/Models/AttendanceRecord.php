<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $table = 'attendance_record';

    protected $primaryKey = null;
    public $incrementing = false;

    // Kolom yang bisa diisi secara massal
    protected $fillable = [
        'schedule_detail_student_id',
        'schedule_detail_week_num',
        'schedule_detail_course_id',
        'schedule_detail_lecturer_nik',
        'schedule_detail_academic_period_id',
        'schedule_detail_course_class',
        'schedule_detail_type',
        'student_id',
        'timestamp',
        'status'
    ];

    public $timestamps = true; // created_at & updated_at otomatis diisi Laravel

    /**
     * Relasi ke tabel Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    /**
     * Relasi ke tabel ScheduleDetail
     */
}
