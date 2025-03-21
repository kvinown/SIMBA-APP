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
        'student_id',
        'week_num',
        'course_id',
        'lecturer_nik',
        'academic_period_id',
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
    public function scheduleDetail()
    {
        return $this->belongsTo(ScheduleDetail::class, [
            'week_num',
            'course_id',
            'lecturer_nik',
            'academic_period_id'
        ], [
            'week_num',
            'course_id',
            'lecturer_nik',
            'academic_period_id'
        ]);
    }
}
