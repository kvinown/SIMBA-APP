@extends('app_layouts.app')

@section('content')
    <div class="container">
        <h1>Schedule Page</h1>

        <!-- Form untuk memilih Academic Period -->
        <form method="GET" action="{{ route('schedule.index') }}">
            <div class="card mb-4">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                        <tr>
                            <td><strong>Period:</strong></td>
                            <td>
                                <select name="academic_period_id" class="form-select" onchange="this.form.submit()">
                                    <option value="">-- Select Period --</option>
                                    @foreach ($periods as $period)
                                        <option value="{{ $period->id }}"
                                            {{ request('academic_period_id') == $period->id ? 'selected' : '' }}>
                                            {{ $period->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>

        <!-- Grouped Schedule -->
        @if(request('academic_period_id'))
            @forelse ($groupedSchedules as $courseName => $schedules)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header fw-bold fs-5">
                        <strong>{{ $courseName }}</strong>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                            <tr>
                                <th>Course Class</th>
                                <th>Type</th>
                                <th>Room</th>
                                <th>Class Information</th>
                                <th>Class Day</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Exam</th>
                                <th>Detail</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($schedules as $schedule)
                                <tr>
                                    <td>{{ $schedule->course_class }}</td>
                                    <td>{{ $schedule->type }}</td>
                                    <td>{{ $schedule->room->name ?? '-' }}</td>
                                    <td>{{ $schedule->addtional_info }}</td>
                                    <td>{{ $schedule->class_day }}</td>
                                    <td>{{ $schedule->start_time }}</td>
                                    <td>{{ $schedule->end_time }}</td>
                                    <td>{{ $schedule->exam }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-info"
                                           href="{{ route('schedule-detail.index', [
                                                    'course_id' => $schedule->course_id,
                                                    'lecturer_nik' => $schedule->lecturer_nik,
                                                    'academic_period_id' => $schedule->academic_period_id,
                                                    'course_class' => $schedule->course_class,
                                                    'type' => $schedule->type,
                                                ]) }}">
                                            Detail
                                        </a>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="alert alert-warning">
                    No schedules found for the selected period.
                </div>
            @endforelse
        @else
            <div class="alert alert-info">
                Please select a period to view schedules.
            </div>
        @endif
    </div>
@endsection
