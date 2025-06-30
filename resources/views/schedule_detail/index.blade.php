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

    <a href="{{ route('schedule.index') }}" class="btn btn-outline-secondary ms-3"><i class="bi bi-arrow-left"></i> {{ $slot ?? 'Back' }}</a>

    <div class="container">
        <h1>Presence Page</h1>

        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <!-- Kolom Kiri: Informasi Kelas -->
                    <div class="col-md-5">
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

                    <!-- Kolom Kanan: Dasbor Ringkasan -->
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Class Summary Dashboard</h5>
                            </div>
                            <div class="card-body">
                                <!-- Grafik Kehadiran -->
                                <div class="mb-3">
                                    <p class="mb-1 fw-bold">Student Count per Week</p>
                                    <canvas id="studentLineChart" aria-label="Line chart of student attendance per week" role="img" style="max-height: 200px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <!-- Ringkasan Mahasiswa Kritis -->
                @if (!empty($riskyStudents['warning']) || !empty($riskyStudents['cekal']))
                    <div class="row">
                        <!-- Mahasiswa Warning -->
                        @if (!empty($riskyStudents['warning']))
                            <div class="col-md">
                                <div class="alert alert-warning p-2">
                                    <strong class="d-block mb-1">Di Ambang Batas (Warning)</strong>
                                    <div class="table-responsive" style="max-height: 150px; overflow-y: auto;">
                                        <table class="table table-sm table-borderless mb-0">
                                            <tbody>
                                            @foreach ($riskyStudents['warning'] as $student)
                                                <tr>
                                                    <td>{{ $student->student->name ?? '-' }} ({{$student->student_id}})</td>
                                                    {{-- REVISI: Menggunakan variabel yang benar dan format teks yang sesuai --}}
                                                    <td class="text-end"><strong> Kehadiran {{ $studentAttendanceData[$student->student_id]['percentage'] }}%</strong></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Mahasiswa Cekal -->
                        @if (!empty($riskyStudents['cekal']))
                            <div class="col-md">
                                <div class="alert alert-danger p-2">
                                    <strong class="d-block mb-1">Sudah di Bawah 75% (Cekal)</strong>
                                    <div class="table-responsive" style="max-height: 150px; overflow-y: auto;">
                                        <table class="table table-sm table-borderless mb-0">
                                            <tbody>
                                            @foreach ($riskyStudents['cekal'] as $student)
                                                <tr>
                                                    <td>{{ $student->student->name ?? '-' }} ({{$student->student_id}})</td>
                                                    {{-- REVISI: Menggunakan variabel yang benar dan format teks yang sesuai --}}
                                                    <td class="text-end"><strong>Kehadiran {{ $studentAttendanceData[$student->student_id]['percentage']  }}%</strong></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                @endif

            </div>
        </div>

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
                    <th>Topic</th>
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
                            {{ \Carbon\Carbon::parse($detail->schedule_date)->format('d M Y') }}, <br>
                            {{ \Carbon\Carbon::parse($detail->course_start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($detail->course_end_time)->format('H:i') }}
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
                        @if($detail->week_num == 15) 'UTS',
                    @elseif($detail->week_num == 16) 'UAS',
                    @else '{{ $detail->week_num }}',
                    @endif
                    @endforeach
                ],
                datasets: [{
                    label: 'Student Count',
                    data: [ @foreach($details as $detail) {{ $detail->student_count }}, @endforeach ],
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
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        bodyColor: '#000',
                        titleColor: '#000'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 4, // Sesuaikan stepSize jika perlu
                            color: '#333'
                        },
                        title: {
                            display: false,
                        }
                    },
                    x: {
                        ticks: { color: '#333' },
                        title: { display: false }
                    }
                }
            }
        });
    </script>
@endpush
