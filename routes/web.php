<?php

use App\Http\Controllers\ProfileController;
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

    Route::get('/lecturer', [\App\Http\Controllers\LecturerController::class, 'index'])->name('lecturer.index');

    Route::get('/schedule', [\App\Http\Controllers\ScheduleController::class, 'index'])->name('schedule.index');

    Route::get('/schedule-detail', [\App\Http\Controllers\ScheduleDetailController::class, 'index'])->name('schedule-detail.index');
    Route::post('/schedule-detail/store', [\App\Http\Controllers\ScheduleDetailController::class, 'store'])->name('schedule-detail.store');
    Route::get('/schedule-detail/update', [\App\Http\Controllers\ScheduleDetailController::class, 'update'])->name('schedule-detail.update');

    Route::get('/enrollment', [\App\Http\Controllers\EnrollmentController::class, 'index'])->name('enrollment.index');

    Route::get('/attendance-record', [\App\Http\Controllers\AttendanceRecordController::class, 'index'])->name('attendance-record.index');
    Route::get('/attendance-record/create', [\App\Http\Controllers\AttendanceRecordController::class, 'create'])->name('attendance-record.create');
    Route::get('/attendance-record/store', [\App\Http\Controllers\AttendanceRecordController::class, 'store'])->name('attendance-record.store');
    Route::put('/attendance-record/update', [\App\Http\Controllers\AttendanceRecordController::class, 'update'])->name('attendance-record.update');

    Route::get('/period', [\App\Http\Controllers\AcademicPeriodController::class, 'index'])->name('period.index');
});

require __DIR__.'/auth.php';
