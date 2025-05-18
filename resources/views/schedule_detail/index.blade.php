@extends('app_layouts.app')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container">
        <h1>Presence Page</h1>

        <!-- Form untuk memilih Academic Period -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <!-- Kolom Kiri: Informasi -->
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <td><strong>Period:</strong></td>
                                <td>{{ $period->name ?? $info['academic_period_id'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Lecturer:</strong></td>
                                <td>{{ $lecturer->name ?? $info['lecturer_nik'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Course:</strong></td>
                                <td>{{ $course->name ?? $info['course_id'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Class:</strong></td>
                                <td>{{ $info['course_class'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Type:</strong></td>
                                <td>{{ ucfirst($info['type']) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Kolom Kanan: Student Count Bar Chart -->
                    <div class="col-md-6">
                        <h5>Student Count per Week</h5>
                        <div class="d-flex align-items-end justify-content-start gap-3 mt-5" style="height: 180px;">
                            @foreach($details as $detail)
                                @php
                                    $percent = $maxStudentCount > 0
                                        ? ($detail->student_count / $maxStudentCount) * 100
                                        : 0;
                                @endphp
                                <div class="text-center">
                                    <div class="vertical-progress d-flex flex-column-reverse" title="{{ $detail->student_count }} students">
                                        <div class="vertical-progress-bar" style="height: {{ $percent }}%;">{{$percent}}%</div>
                                    </div>
                                    <small class="d-block mt-1"><strong>W{{ $detail->week_num }}</strong></small>
                                    <small>{{ $detail->student_count }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <!-- Tombol Add Presence -->
            <div class="mb-3">
                <a class="btn btn-primary" href="{{route('attendance-record.create', ['data' => $info])}}">+ Add Presence</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                    <tr>
                        <th>Week</th>
                        <th>Date</th>
                        <th>Topic</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Student Count</th>
                        <th>Class Information</th>
                        <th>Class Checked</th>
                        <th>File</th>
                        <th>Confirm Date</th>
                        <th>Detail</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($details as $detail)
                        <tr>
                            <td>{{ $detail->week_num }}</td>
                            <td>{{ $detail->schedule_date }}</td>
                            <td>{{ $detail->topic }}</td>
                            <td>{{ $detail->course_start_time }}</td>
                            <td>{{ $detail->course_end_time }}</td>
                            <td>{{ $detail->student_count }}</td>
                            <td>{{ $detail->class_information }}</td>
                            <td>{{ $detail->checked }}</td>
                            <td>{{ $detail->file_path }}</td>
                            <td>{{ $detail->confirmation_date }}</td>
                            <td>
                                <a class="btn btn-sm btn-info"
                                   href="{{ route('attendance-record.index', [
                                        'schedule_detail_week_num' => $detail->week_num,
                                        'schedule_detail_course_id' => $detail->course_id,
                                        'schedule_detail_lecturer_nik' => $detail->lecturer_nik,
                                        'schedule_detail_academic_period_id' => $detail->academic_period_id,
                                        'schedule_detail_course_class' => $detail->course_class,
                                        'schedule_detail_type' => $detail->type,
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
@endsection
