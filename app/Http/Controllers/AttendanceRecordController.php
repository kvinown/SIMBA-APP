<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\AttendanceRecord;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lecturer;
use App\Models\ScheduleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AttendanceRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'schedule_detail_week_num' => 'required|integer',
            'schedule_detail_course_id' => 'required|string',
            'schedule_detail_lecturer_nik' => 'required|string',
            'schedule_detail_academic_period_id' => 'required|string',
            'schedule_detail_course_class' => 'required|string',
            'schedule_detail_type' => 'required|string',
        ]);

        $attendances = AttendanceRecord::where($validated)->get();

        $course = Course::find($validated['schedule_detail_course_id']);
        $lecturer = Lecturer::find($validated['schedule_detail_lecturer_nik']);
        $period = AcademicPeriod::find($validated['schedule_detail_academic_period_id']);

        return view('attendance_record.index', [
            'attendances' => $attendances,
            'info' => $validated,
            'course' => $course,
            'lecturer' => $lecturer,
            'period' => $period,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $data = $request->query('data');

        // Ambil student_id berdasarkan field yang sesuai di tabel enrollment
        $students = Enrollment::where('schedule_course_id', $data['course_id'])
            ->where('schedule_lecturer_nik', $data['lecturer_nik'])
            ->where('schedule_academic_period_id', $data['academic_period_id'])
            ->where('schedule_course_class', $data['course_class'])
            ->where('schedule_type', $data['type'])
            ->with('student') // penting: eager load relasi
            ->get();

        $studentCount = $students->count();

        $latestWeekNum = AttendanceRecord::where([
            'schedule_detail_course_id' => $data['course_id'],
            'schedule_detail_lecturer_nik' => $data['lecturer_nik'],
            'schedule_detail_academic_period_id' => $data['academic_period_id'],
            'schedule_detail_course_class' => $data['course_class'],
            'schedule_detail_type' => $data['type'],
        ])->max('schedule_detail_week_num');

        $nextWeekNum = $latestWeekNum ? $latestWeekNum + 1 : 1;

        return view('attendance_record.create', [
            'students' => $students,
            'data' => $data,
            'studentCount' => $studentCount,
            'nextWeekNum' => $nextWeekNum,
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->old(); // Ambil dari withInput()

        if (!$data || !isset($data['status'])) {
            return redirect()->route('schedule-detail.index')->with('error', 'No data provided.');
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
