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
                            <tr>
                                <td><strong>Student Total:</strong></td>
                                <td>{{ @$maxStudentCount }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h5>Student Count per Week</h5>
                        <canvas id="studentLineChart" aria-label="Line chart of student attendance per week" role="img" min-height="125" height="auto"></canvas>
                    </div>
                </div>
            </div>
        </div>


        <!-- Tombol Add Presence -->
            <div class="mb-3">
                <a class="btn btn-primary" href="{{ route('attendance-record.create', [
                    'course_id' => $info['course_id'],
                    'academic_period_id' => $info['academic_period_id'],
                    'course_class' => $info['course_class'],
                    'type' => $info['type'],
                ]) }}">+ Add Presence</a>
            </div>


            <div class="table-responsive">
                <table class="table table-bordered datatable-export">
                    <thead class="table-dark">
                    <tr>
                        <th>Week</th>
                        <th>Lecturer Material (Topic)</th>
                        <th>Date</th>
                        <th>Student Count</th>
                        <th>Class Information</th>
                        <th>File</th>
                        <th>Confirm Date</th>
                        <th>Action</th>
                        <th>Detail</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($details as $detail)
                        <tr>
                            <td>{{ $detail->week_num }}</td>
                            <td>{{ $detail->topic }}</td>
                            <td>
                                {{ $detail->schedule_date }}, <br>
                                {{ $detail->course_start_time }} - {{ $detail->course_end_time }}
                            </td>
                            <td>{{ $detail->student_count }}</td>
                            <td>{{ $detail->class_information }}</td>
                            <td>{{ $detail->file_path }}</td>
                            <td>
                                @if($detail->checked)
                                {{ $detail->confirmation_date }}
                                @else
                                    Data is not confirmed
                                @endif
                            </td>
                            <td class="d-flex">
                                @if($detail->checked)
                                    Data is confirmed
                                @else
                                    Data is not confirmed
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-sm btn-info" href="{{ route('attendance-record.index', [
                                    $detail->week_num,
                                    $detail->course_id,
                                    $detail->academic_period_id,
                                    $detail->course_class,
                                    $detail->type,
                                ]) }}">
                                    Detail
                                </a>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
    </div>
@endsection
@push('scripts')
    <script>
        const ctx = document.getElementById('studentLineChart').getContext('2d');

        const studentLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($details as $detail)
                        @if($detail->week_num == 15)
                            'UTS',
                        @elseif($detail->week_num == 16)
                            'UAS',
                        @else
                            '{{ $detail->week_num }}',
                        @endif
                    @endforeach
                ],
                datasets: [{
                    label: 'Student Count',
                    data: [
                        @foreach($details as $detail)
                            {{ $detail->student_count }},
                        @endforeach
                    ],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.2)',
                    tension: 0.3,
                    pointBackgroundColor: '#0d6efd',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#0d6efd',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: '#000' // warna legend text
                        }
                    },
                    tooltip: {
                        bodyColor: '#000',
                        titleColor: '#000'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0,
                            color: '#000' // warna angka sumbu Y
                        },
                        title: {
                            display: true,
                            text: 'Number of Students',
                            color: '#000' // warna judul sumbu Y
                        }
                    },
                    x: {
                        ticks: {
                            color: '#000' // warna angka sumbu X
                        },
                        title: {
                            display: true,
                            text: 'Week',
                            color: '#000' // warna judul sumbu X
                        }
                    }
                }
            }
        });
    </script>
@endpush
