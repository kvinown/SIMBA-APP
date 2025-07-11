<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $nik = Auth::user()->nik;
        $academicPeriodId = $request->query('academic_period_id');

        $query = Schedule::with(['course', 'room', 'academicPeriod']) // Eager loading
        ->where('lecturer_nik', $nik);

        if ($academicPeriodId) {
            $query->where('academic_period_id', $academicPeriodId);
        }

        $schedules = $query->get();

        // Group berdasarkan nama course
        $groupedSchedules = $schedules->groupBy(function ($schedule) {
            return $schedule->course->name ?? 'Unknown Course';
        });

        $periods = AcademicPeriod::all();

        return view('schedule.index', [
            'groupedSchedules' => $groupedSchedules,
            'periods' => $periods,
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
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
