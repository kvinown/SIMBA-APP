@extends('app_layouts.app')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container">
        <h1>Presence Detail Page</h1>


        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-borderless">
                    <tbody>
                    <tr>
                        <td><strong>Week:</strong></td>
                        <td>{{ $info['schedule_detail_week_num'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Period:</strong></td>
                        <td>{{ $period->name ?? $info['schedule_detail_academic_period_id'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Lecturer:</strong></td>
                        <td>{{ $lecturer->name ?? $info['schedule_detail_lecturer_nik'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Course:</strong></td>
                        <td>{{ $course->name ?? $info['schedule_detail_course_id'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Class:</strong></td>
                        <td>{{ $info['schedule_detail_course_class'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Type:</strong></td>
                        <td>{{ $info['schedule_detail_type'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Student Count:</strong></td>
                        <td>{{ $student_count }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered datatable-export">
                <thead class="table-dark">
                <tr>
                    <th>NRP</th>
                    <th>Student Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->student->id }}</td>
                        <td>{{ $attendance->student->name }}</td>
                        <td>
                            @switch($attendance->status)
                                @case(0)
                                    Tidak Hadir
                                    @break
                                @case(1)
                                    Hadir
                                    @break
                                @case(2)
                                    Sakit
                                    @break
                                @case(3)
                                    Izin
                                    @break
                            @endswitch
                        </td>
                        <td>
                            <form action="{{ route('attendance-record.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="schedule_detail_week_num" value="{{ $attendance->schedule_detail_week_num }}">
                                <input type="hidden" name="schedule_detail_course_id" value="{{ $attendance->schedule_detail_course_id }}">
                                <input type="hidden" name="schedule_detail_lecturer_nik" value="{{ $attendance->schedule_detail_lecturer_nik }}">
                                <input type="hidden" name="schedule_detail_academic_period_id" value="{{ $attendance->schedule_detail_academic_period_id }}">
                                <input type="hidden" name="schedule_detail_course_class" value="{{ $attendance->schedule_detail_course_class }}">
                                <input type="hidden" name="schedule_detail_type" value="{{ $attendance->schedule_detail_type }}">
                                <input type="hidden" name="student_id" value="{{ $attendance->student_id }}">

                                @php $uid = uniqid(); @endphp
                                <div class="btn-group" role="group" aria-label="Status Kehadiran">
                                    <input type="radio" class="btn-check" name="status" id="hadir_{{ $attendance->id }}_{{ $uid }}" value="1" autocomplete="off" {{ $attendance->status == 1 ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="btn btn-outline-success btn-sm" for="hadir_{{ $attendance->id }}_{{ $uid }}">Hadir</label>

                                    <input type="radio" class="btn-check" name="status" id="tidak_{{ $attendance->id }}_{{ $uid }}" value="0" autocomplete="off" {{ $attendance->status == 0 ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="btn btn-outline-danger btn-sm" for="tidak_{{ $attendance->id }}_{{ $uid }}">Tidak Hadir</label>

                                    <input type="radio" class="btn-check" name="status" id="sakit_{{ $attendance->id }}_{{ $uid }}" value="2" autocomplete="off" {{ $attendance->status == 2 ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="btn btn-outline-warning btn-sm" for="sakit_{{ $attendance->id }}_{{ $uid }}">Sakit</label>

                                    <input type="radio" class="btn-check" name="status" id="izin_{{ $attendance->id }}_{{ $uid }}" value="3" autocomplete="off" {{ $attendance->status == 3 ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="btn btn-outline-secondary btn-sm" for="izin_{{ $attendance->id }}_{{ $uid }}">Izin</label>
                                </div>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
