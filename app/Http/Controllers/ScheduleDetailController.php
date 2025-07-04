<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\AttendanceRecord;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lecturer;
use App\Models\ScheduleDetail;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nette\Utils\Arrays;

class ScheduleDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($course_id, $academic_period_id, $course_class, $type)
    {
        $lecturer_nik = Auth::user()->nik;
        $data = [
            'course_id' => $course_id,
            'lecturer_nik' => $lecturer_nik,
            'academic_period_id' => $academic_period_id,
            'course_class' => $course_class,
            'type' => $type,
        ];

        // --- Logika yang sudah ada ---
        $details = ScheduleDetail::where($data)->orderBy('week_num', 'asc')->get();
        $course = Course::find($data['course_id']);
        $lecturer = Lecturer::where('nik', $data['lecturer_nik'])->first();
        $period = AcademicPeriod::find($data['academic_period_id']);
        $students = Enrollment::where('schedule_course_id', $data['course_id'])
            ->where('schedule_lecturer_nik', $data['lecturer_nik'])
            ->where('schedule_academic_period_id', $data['academic_period_id'])
            ->where('schedule_course_class', $data['course_class'])
            ->where('schedule_type', $data['type'])
            ->with('student')
            ->get();
        $maxStudentCount = $students->count();

        // --- Logika Menghitung Mahasiswa Berisiko ---
        $riskyStudents = [
            'warning' => [],
            'cekal' => [],
        ];
        $studentAttendanceData = [];

        $meetingsSoFar = $details->count();

        // REVISI: Hanya jalankan logika jika sudah ada pertemuan
        if ($meetingsSoFar > 2) {
            $allAttendanceRecords = AttendanceRecord::where([
                'schedule_detail_course_id' => $course_id,
                'schedule_detail_lecturer_nik' => $lecturer_nik,
                'schedule_detail_academic_period_id' => $academic_period_id,
                'schedule_detail_course_class' => $course_class,
                'schedule_detail_type' => $type,
            ])->get()->groupBy('student_id');

            foreach ($students as $student) {
                $student_id = $student->student_id;
                $records = $allAttendanceRecords->get($student_id, collect());

                $presentCount = $records->whereIn('status', [1, 2, 3])->count();

                // Hitung persentase kehadiran saat ini
                $currentPercentage = ($presentCount / $meetingsSoFar) * 100;

                // Hitung persentase hipotetis jika mahasiswa absen di pertemuan berikutnya
                $futureMeetings = $meetingsSoFar + 1;
                $futurePercentage = ($presentCount / $futureMeetings) * 100;

                $status = 'safe';
                if ($currentPercentage < 75) {
                    $status = 'cekal';
                    $riskyStudents['cekal'][] = $student;
                }
                elseif ($currentPercentage >= 75 && $futurePercentage < 75) {
                    $status = 'warning';
                    $riskyStudents['warning'][] = $student;
                }

                $studentAttendanceData[$student_id] = [
                    'status' => $status,
                    'percentage' => round($currentPercentage),
                ];
            }
        }
        // --- AKHIR REVISI ---

        return view('schedule_detail.index', [
            'details' => $details,
            'info' => $data,
            'course' => $course,
            'lecturer' => $lecturer,
            'period' => $period,
            'maxStudentCount' => $maxStudentCount,
            'riskyStudents' => $riskyStudents,
            'studentAttendanceData' => $studentAttendanceData,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|string',
            'lecturer_nik' => 'required|string',
            'academic_period_id' => 'required|string',
            'course_class' => 'required|string',
            'type' => 'required|string',
            'week_num' => 'required|integer',
            'schedule_date' => 'required|date',
            'topic' => ['required', 'string', 'max:255'],
            'course_start_time' => 'required|date_format:H:i',
            'course_end_time' => 'required|date_format:H:i',
            'student_count' => 'required|integer',
            'class_information' => 'required|string',
            'status' => 'required|array',
        ], [
            'status.required' => 'Silakan pilih status kehadiran untuk semua mahasiswa.',
        ]);

        $students = Enrollment::where('schedule_course_id', $validated['course_id'])
            ->where('schedule_lecturer_nik', $validated['lecturer_nik'])
            ->where('schedule_academic_period_id', $validated['academic_period_id'])
            ->where('schedule_course_class', $validated['course_class'])
            ->where('schedule_type', $validated['type'])
            ->pluck('student_id');

        $missingStatus = [];

        foreach ($students as $student_id) {
            if (!array_key_exists($student_id, $validated['status'])) {
                $student = Student::find($student_id);
                $missingStatus[] = $student ? "$student->name ($student_id)" : $student_id;
            }
        }

        if (!empty($missingStatus)) {
            $message = 'Status kehadiran belum diisi untuk: ' . implode(', ', $missingStatus);
            return redirect()->back()
                ->withErrors(['status' => $message])
                ->withInput();
        }


        $exists = ScheduleDetail::where([
            'course_id' => $validated['course_id'],
            'lecturer_nik' => $validated['lecturer_nik'],
            'academic_period_id' => $validated['academic_period_id'],
            'course_class' => $validated['course_class'],
            'type' => $validated['type'],
            'week_num' => $validated['week_num'],
        ])->exists();

        if ($exists) {
            return redirect()->route('attendance-record.create', [
                'course_id' => $validated['course_id'],
                'academic_period_id' => $validated['academic_period_id'],
                'course_class' => $validated['course_class'],
                'type' => $validated['type'],
            ])->withErrors(['duplicate' => 'Data untuk minggu ini sudah ada.'])->withInput();
        }

        // Simpan ke ScheduleDetail
        $scheduleDetail = new ScheduleDetail($validated);
        $scheduleDetail->save();

        return redirect()->route('attendance-record.store')->withInput($validated);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $data = $request->old();
        $studentCount = ScheduleDetail::where([
            'week_num' => $data['schedule_detail_week_num'],
            'course_id' => $data['schedule_detail_course_id'],
            'lecturer_nik' => $data['schedule_detail_lecturer_nik'],
            'academic_period_id' => $data['schedule_detail_academic_period_id'],
            'course_class' => $data['schedule_detail_course_class'],
            'type' => $data['schedule_detail_type'],
        ])->value('student_count');

        ScheduleDetail::where([
            'week_num' => $data['schedule_detail_week_num'],
            'course_id' => $data['schedule_detail_course_id'],
            'lecturer_nik' => $data['schedule_detail_lecturer_nik'],
            'academic_period_id' => $data['schedule_detail_academic_period_id'],
            'course_class' => $data['schedule_detail_course_class'],
            'type' => $data['schedule_detail_type'],
        ])->update([
            'student_count' => $studentCount + $data['student_count'],
        ]);

        return redirect()->back()->with('success', 'Presensi berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
