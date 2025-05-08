@extends('app_layouts.app')

@section('content')
    <div class="container">
        <h1>Enrollment Page</h1>

        @foreach ($groupedEnrollments as $key => $enrollments)
            @php
                $first = $enrollments->first();
                $schedule = $first->schedule();
            @endphp

            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                        <tr>
                            <td><strong>Course:</strong></td>
                            <td>{{ $schedule?->course->name ?? $first->schedule_course_id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Class:</strong></td>
                            <td>{{ $first->schedule_course_class }}</td>
                        </tr>
                        <tr>
                            <td><strong>Type:</strong></td>
                            <td>{{ ucfirst($first->schedule_type) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Period:</strong></td>
                            <td>{{ $schedule?->academicPeriod->name ?? $first->schedule_academic_period_id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Lecturer:</strong></td>
                            <td>{{ $schedule?->lecturer->name ?? $first->schedule_lecturer_nik }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="table-responsive mb-5">
                <table class="table table-bordered">
                    <thead class="table-dark">
                    <tr>
                        <th>NRP</th>
                        <th>Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($enrollments as $enrollment)
                        <tr>
                            <td>{{ $enrollment->student_id }}</td>
                            <td>{{ $enrollment->student->name }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
@endsection
