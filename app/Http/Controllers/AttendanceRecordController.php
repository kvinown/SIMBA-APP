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
            ->with('student')
            ->get();

        $studentCount = $students->count();

        $latestWeekNum = AttendanceRecord::where([
            'schedule_detail_course_id' => $course_id,
            'schedule_detail_lecturer_nik' => $lecturer_nik,
            'schedule_detail_academic_period_id' => $academic_period_id,
            'schedule_detail_course_class' => $course_class,
            'schedule_detail_type' => $type,
        ])->max('schedule_detail_week_num');

        $nextWeekNum = $latestWeekNum ? $latestWeekNum + 1 : 1;

        $schedule = Schedule::where('course_id', $course_id)
            ->where('lecturer_nik', $lecturer_nik)
            ->where('academic_period_id', $academic_period_id)
            ->where('course_class', $course_class)
            ->where('type', $type)
            ->first();

        return view('attendance_record.create', [
            'students' => $students,
            'schedule' => $schedule,
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
