<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use HasFactory;

    // Tentukan nama tabel
    protected $table = 'enrollment';

    // Tentukan primary key jika berbeda dari 'id'
    protected $primaryKey = null; // Karena tidak ada auto increment id

    // Tentukan kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'student_id',
        'schedule_course_id',
        'schedule_lecturer_nik',
        'schedule_academic_period_id',
        'schedule_course_class',
        'schedule_type',
    ];

    // Tentukan tipe kolom untuk timestamps
    public $timestamps = true;

    // Relasi dengan model Student
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    // app/Models/Enrollment.php

    public function schedule()
    {
        return Schedule::where('course_id', $this->schedule_course_id)
            ->where('lecturer_nik', $this->schedule_lecturer_nik)
            ->where('academic_period_id', $this->schedule_academic_period_id)
            ->where('course_class', $this->schedule_course_class)
            ->where('type', $this->schedule_type)
            ->first(); // ambil satu, karena satu kombinasi unik
    }

}
