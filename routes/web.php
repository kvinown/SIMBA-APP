<?php

use App\Http\Controllers\AcademicPeriodController;
use App\Http\Controllers\AttendanceRecordController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ScheduleDetailController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dash', function () {
    return view('dashboard.index');
})->middleware(['auth', 'verified'])->name('dash');

Route::get('/example', function () {
    return view('presence.presence');
})->name('example');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/lecturer', [LecturerController::class, 'index'])->name('lecturer.index');

    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');

    Route::get('/schedule-detail/{course_id}/{academic_period_id}/{course_class}/{type}', [ScheduleDetailController::class, 'index'])->name('schedule-detail.index');

    Route::post('/schedule-detail/store', [ScheduleDetailController::class, 'store'])->name('schedule-detail.store');
    Route::get('/schedule-detail/update', [ScheduleDetailController::class, 'update'])->name('schedule-detail.update');

    Route::get('/enrollment', [\App\Http\Controllers\EnrollmentController::class, 'index'])->name('enrollment.index');

    Route::get('/attendance/check-week', [AttendanceRecordController::class, 'checkWeekExistence'])->name('attendance.checkWeek');
    Route::get('/attendance-record/create/{course_id}/{academic_period_id}/{course_class}/{type}',
        [AttendanceRecordController::class, 'create'])
        ->name('attendance-record.create');

    Route::get('/attendance-record/{week_num}/{course_id}/{academic_period_id}/{course_class}/{type}',
        [AttendanceRecordController::class, 'index'])
        ->name('attendance-record.index');

    Route::get('/attendance-record/store', [AttendanceRecordController::class, 'store'])->name('attendance-record.store');
    Route::put('/attendance-record/update', [AttendanceRecordController::class, 'update'])->name('attendance-record.update');

    Route::get('/period', [AcademicPeriodController::class, 'index'])->name('period.index');
});

require __DIR__.'/auth.php';
