<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lecturer;
use App\Models\ScheduleDetail;
use Illuminate\Http\Request;
use Nette\Utils\Arrays;

class ScheduleDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|string',
            'lecturer_nik' => 'required|string',
            'academic_period_id' => 'required|string',
            'course_class' => 'required|string',
            'type' => 'required|string',
        ]);

        $details = ScheduleDetail::where($validated)->get();

        // Ambil data dari model terkait berdasarkan ID
        $course = Course::find($validated['course_id']);
        $lecturer = Lecturer::where('nik', $validated['lecturer_nik'])->first();
        $period = AcademicPeriod::find($validated['academic_period_id']);

        $students = Enrollment::where('schedule_course_id', $validated['course_id'])
            ->where('schedule_lecturer_nik', $validated['lecturer_nik'])
            ->where('schedule_academic_period_id', $validated['academic_period_id'])
            ->where('schedule_course_class', $validated['course_class'])
            ->where('schedule_type', $validated['type'])
            ->with('student') // penting: eager load relasi
            ->get();

        $maxStudentCount = $students->count();

        return view('schedule_detail.index', [
            'details' => $details,
            'info' => $validated,
            'course' => $course,
            'lecturer' => $lecturer,
            'period' => $period,
            'maxStudentCount' => $maxStudentCount,
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
            'topic' => 'required|string|max:255',
            'course_start_time' => 'required|date_format:H:i',
            'course_end_time' => 'required|date_format:H:i',
            'student_count' => 'required|integer',
            'class_information' => 'required|string',
            'status' => 'required|array', // tetap bawa ini untuk attendance nanti
        ]);

        // Simpan ke ScheduleDetail
        $scheduleDetail = new ScheduleDetail($validated);
        $scheduleDetail->save();

        // Redirect ke route AttendanceRecordController@store, pakai session atau redirect dengan data
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
