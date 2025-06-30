<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\AttendanceRecord;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lecturer;
use App\Models\Schedule;
use App\Models\ScheduleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

    class AttendanceRecordController extends Controller
{
    private const TOTAL_MEETINGS = 14;
    /**
     * Display a listing of the resource.
     */
    public function index(
        $week_num,
        $course_id,
        $academic_period_id,
        $course_class,
        $type
    )
    {
        $lecturer_nik = Auth::user()->nik;

        $data = [
            'schedule_detail_week_num' => (int) $week_num,
            'schedule_detail_course_id' => $course_id,
            'schedule_detail_lecturer_nik' => $lecturer_nik,
            'schedule_detail_academic_period_id' => $academic_period_id,
            'schedule_detail_course_class' => $course_class,
            'schedule_detail_type' => $type,
        ];

        $attendances = AttendanceRecord::where($data)->get();
        $student_count = ScheduleDetail::where([
            ['week_num', '=', $week_num],
            ['course_id', '=', $course_id],
            ['lecturer_nik', '=', $lecturer_nik],
            ['academic_period_id', '=', $academic_period_id],
            ['course_class', '=', $course_class],
            ['type', '=', $type],
        ])->value('student_count');


        $course = Course::find($course_id);
        $lecturer = Lecturer::find($lecturer_nik);
        $period = AcademicPeriod::find($academic_period_id);

        return view('attendance_record.index', [
            'attendances' => $attendances,
            'info' => $data,
            'course' => $course,
            'lecturer' => $lecturer,
            'period' => $period,
            'student_count' => $student_count,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
        public function create(
            $course_id,
            $academic_period_id,
            $course_class,
            $type
        ) {
            $lecturer_nik = Auth::user()->nik;
            $data = [
                'course_id' => $course_id,
                'lecturer_nik' => $lecturer_nik,
                'academic_period_id' => $academic_period_id,
                'course_class' => $course_class,
                'type' => $type,
            ];

            $students = Enrollment::where('schedule_course_id', $course_id)
                ->where('schedule_lecturer_nik', $lecturer_nik)
                ->where('schedule_academic_period_id', $academic_period_id)
                ->where('schedule_course_class', $course_class)
                ->where('schedule_type', $type)
                ->with('student')->get();

            $schedule = Schedule::where($data)->first();

            $latestWeekNum = ScheduleDetail::where($data)->max('week_num');
            $nextWeekNum = $latestWeekNum ? $latestWeekNum + 1 : 1;

            // --- REVISI: Menyamakan logika & struktur data dengan ScheduleDetailController ---
            $riskyStudents = ['warning' => [], 'cekal' => []];
            $studentAttendanceData = [];

            $allAttendanceRecords = AttendanceRecord::where([
                'schedule_detail_course_id' => $course_id,
                'schedule_detail_lecturer_nik' => $lecturer_nik,
                'schedule_detail_academic_period_id' => $academic_period_id,
                'schedule_detail_course_class' => $course_class,
                'schedule_detail_type' => $type,
            ])->get()->groupBy('student_id');

            $meetingsSoFar = ScheduleDetail::where($data)->count();

            foreach ($students as $student) {
                $student_id = $student->student_id;
                $records = $allAttendanceRecords->get($student_id, collect());
                $presentCount = $records->whereIn('status', [1, 2, 3])->count();

                // Hitung persentase kehadiran saat ini
                $currentPercentage = ($meetingsSoFar > 0) ? ($presentCount / $meetingsSoFar) * 100 : 100;

                // Hitung persentase hipotetis jika mahasiswa absen di pertemuan berikutnya
                $futureMeetings = $meetingsSoFar + 1;
                $futurePercentage = ($futureMeetings > 0) ? ($presentCount / $futureMeetings) * 100 : 0;

                $status = 'safe';
                // Cekal: Jika persentase saat ini sudah di bawah 75%
                if ($currentPercentage < 75) {
                    $status = 'cekal';
                    $riskyStudents['cekal'][] = $student;
                }
                // Warning: Jika persentase saat ini >= 75%, TAPI akan menjadi < 75% jika absen lagi
                elseif ($currentPercentage >= 75 && $futurePercentage < 75) {
                    $status = 'warning';
                    $riskyStudents['warning'][] = $student;
                }

                // Simpan data untuk ditampilkan di view
                $studentAttendanceData[$student_id] = [
                    'status' => $status,
                    'percentage' => round($currentPercentage),
                ];
            }
            // --- AKHIR REVISI ---

            return view('attendance_record.create', [
                'students' => $students,
                'schedule' => $schedule,
                'data' => $data,
                'nextWeekNum' => $nextWeekNum,
                'riskyStudents' => $riskyStudents,
                'studentAttendanceData' => $studentAttendanceData,
            ]);
        }



        /**
         * Check if attendance for a specific week already exists.
         * This is intended to be called via AJAX.
         */
        public function checkWeekExistence(Request $request)
        {
            // Validasi input dari request AJAX
            $validated = $request->validate([
                'week_num' => 'required|integer',
                'course_id' => 'required|string',
                'academic_period_id' => 'required|string',
                'course_class' => 'required|string',
                'type' => 'required|string',
            ]);

            // Dapatkan NIK dosen yang sedang login
            $lecturer_nik = Auth::user()->nik;

            // Lakukan pengecekan ke tabel schedule_details
            $exists = ScheduleDetail::where([
                'week_num' => $validated['week_num'],
                'course_id' => $validated['course_id'],
                'lecturer_nik' => $lecturer_nik,
                'academic_period_id' => $validated['academic_period_id'],
                'course_class' => $validated['course_class'],
                'type' => $validated['type'],
            ])->exists(); // exists() lebih efisien karena hanya mengecek keberadaan data

            // Kembalikan response dalam format JSON
            return response()->json(['exists' => $exists]);
        }



    /**
     * Store a newly created resource in storage.
     * Menerima data dari ScheduleDetailController dan menyimpan ke attendance_records.
     */
    public function store(Request $request)
    {
        $data = $request->old(); // Ambil dari withInput()

        if (!$data || !isset($data['status'])) {
            return redirect()->route('attendance-record.create', [
                'course_id' => $data['course_id'] ?? null,
                'academic_period_id' => $data['academic_period_id'] ?? null,
                'course_class' => $data['course_class'] ?? null,
                'type' => $data['type'] ?? null,
            ])->with('error', 'Data tidak ditemukan atau status kosong.');
        }

        foreach ($data['status'] as $student_id => $status) {
            AttendanceRecord::create([
                'schedule_detail_week_num' => $data['week_num'],
                'schedule_detail_course_id' => $data['course_id'],
                'schedule_detail_lecturer_nik' => $data['lecturer_nik'],
                'schedule_detail_academic_period_id' => $data['academic_period_id'],
                'schedule_detail_course_class' => $data['course_class'],
                'schedule_detail_type' => $data['type'],
                'student_id' => $student_id,
                'status' => $status,
            ]);
        }

        return redirect()->route('schedule-detail.index', [
            'course_id' => $data['course_id'],
            'lecturer_nik' => $data['lecturer_nik'],
            'academic_period_id' => $data['academic_period_id'],
            'course_class' => $data['course_class'],
            'type' => $data['type'],
        ])->with('success', 'Presensi berhasil ditambahkan.');
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
        $validated = $request->validate([
            'status' => 'required|in:0,1,2,3',
            'schedule_detail_week_num' => 'required|integer',
            'schedule_detail_course_id' => 'required|string',
            'schedule_detail_lecturer_nik' => 'required|string',
            'schedule_detail_academic_period_id' => 'required|string',
            'schedule_detail_course_class' => 'required|string',
            'schedule_detail_type' => 'required|string',
            'student_id' => 'required',
        ]);
        $attendance = AttendanceRecord::where([
            'schedule_detail_week_num' => $validated['schedule_detail_week_num'],
            'schedule_detail_course_id' => $validated['schedule_detail_course_id'],
            'schedule_detail_lecturer_nik' => $validated['schedule_detail_lecturer_nik'],
            'schedule_detail_academic_period_id' => $validated['schedule_detail_academic_period_id'],
            'schedule_detail_course_class' => $validated['schedule_detail_course_class'],
            'schedule_detail_type' => $validated['schedule_detail_type'],
            'student_id' => $validated['student_id'],
        ])->first();

        AttendanceRecord::where([
            'schedule_detail_week_num' => $validated['schedule_detail_week_num'],
            'schedule_detail_course_id' => $validated['schedule_detail_course_id'],
            'schedule_detail_lecturer_nik' => $validated['schedule_detail_lecturer_nik'],
            'schedule_detail_academic_period_id' => $validated['schedule_detail_academic_period_id'],
            'schedule_detail_course_class' => $validated['schedule_detail_course_class'],
            'schedule_detail_type' => $validated['schedule_detail_type'],
            'student_id' => $validated['student_id'],
        ])->update([
            'status' => $validated['status'],
        ]);


        $oldStatus = $attendance->status;
        $newStatus = $validated['status'];


        if ($oldStatus == $newStatus) {
            $validated['student_count'] = 0;
        } elseif ($oldStatus != 0 && $newStatus != 0) {
            $validated['student_count'] = 0;
        } elseif ($oldStatus == 0 && $newStatus != 0) {
            $validated['student_count'] = 1;
        } elseif ($oldStatus != 0 && $newStatus == 0) {
            $validated['student_count'] = -1;
        }


        return redirect()->route('schedule-detail.update')->withInput($validated);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
