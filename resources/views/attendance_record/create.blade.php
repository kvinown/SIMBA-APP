@extends('app_layouts.app')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="container">
        <h1>Presence Store Page</h1>

        <!-- Form Presence -->
        <form method="POST" action="{{ route('schedule-detail.store') }}">
            @csrf
            <div class="card mb-4">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                        <tr>
                            <td><label for="week_num" class="form-label"><strong>Week:</strong></label></td>
                            <td><input type="number" id="week_num" class="form-control" name="week_num" value="{{ old('week_num', $nextWeekNum) }}" required></td>
                        </tr>
                        <tr>
                            <td><label for="schedule_date" class="form-label"><strong>Input Date:</strong></label></td>
                            <td><input type="date" id="schedule_date" class="form-control" name="schedule_date" value="{{ date('Y-m-d') }}" required></td>
                        </tr>
                        <tr>
                            <td><label for="topic" class="form-label"><strong>Topic:</strong></label></td>
                            <td><input type="text" id="topic" class="form-control" name="topic" required></td>
                        </tr>
                        <tr>
                            <td><label for="course_start_time" class="form-label"><strong>Start Time:</strong></label></td>
                            <td><input type="time" id="course_start_time" class="form-control" name="course_start_time" value="{{ old('course_start_time', '08:00') }}" required></td>
                        </tr>
                        <tr>
                            <td><label for="course_end_time" class="form-label"><strong>End Time:</strong></label></td>
                            <td><input type="time" id="course_end_time" class="form-control" name="course_end_time" value="{{ old('course_end_time', '10:00') }}" required></td>
                        </tr>
                        <tr>
                            <td><label for="class_information" class="form-label"><strong>Class Information:</strong></label></td>
                            <td><textarea id="class_information" class="form-control" name="class_information" required>{{ old('class_information', 'Introduction to the course') }}</textarea></td>
                        </tr>

                        <!-- Hidden Fields -->
                        <input type="hidden" name="course_id" value="{{ $data['course_id'] }}">
                        <input type="hidden" name="lecturer_nik" value="{{ $data['lecturer_nik'] }}">
                        <input type="hidden" name="academic_period_id" value="{{ $data['academic_period_id'] }}">
                        <input type="hidden" name="course_class" value="{{ $data['course_class'] }}">
                        <input type="hidden" name="type" value="{{ $data['type'] }}">
                        <input type="hidden" name="student_count" value="{{ $studentCount }}">
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tombol Pilihan Massal -->
            <div class="mb-3">
                <strong>Set All Status:</strong>
                <button type="button" id="btn_all_hadir" class="btn btn-outline-success btn-sm status-all-btn" onclick="setAllStatus(1, this)">Hadir</button>
                <button type="button" id="btn_all_tidak" class="btn btn-outline-danger btn-sm status-all-btn" onclick="setAllStatus(0, this)">Tidak Hadir</button>
                <button type="button" id="btn_all_sakit" class="btn btn-outline-warning btn-sm status-all-btn" onclick="setAllStatus(2, this)">Sakit</button>
                <button type="button" id="btn_all_alpha" class="btn btn-outline-secondary btn-sm status-all-btn" onclick="setAllStatus(3, this)">Alpha</button>
            </div>

            <!-- Tabel Input Status Kehadiran -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                    <tr>
                        <th>NRP</th>
                        <th>Name</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>{{ $student->student_id }}</td>
                            <td>{{ $student->student->name ?? '-' }}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Status Kehadiran">
                                    <input type="radio" class="btn-check" name="status[{{ $student->student_id }}]" id="hadir_{{ $student->student_id }}" value="1" autocomplete="off">
                                    <label class="btn btn-outline-success btn-sm" for="hadir_{{ $student->student_id }}">Hadir</label>

                                    <input type="radio" class="btn-check" name="status[{{ $student->student_id }}]" id="tidak_{{ $student->student_id }}" value="0" autocomplete="off">
                                    <label class="btn btn-outline-danger btn-sm" for="tidak_{{ $student->student_id }}">Tidak Hadir</label>

                                    <input type="radio" class="btn-check" name="status[{{ $student->student_id }}]" id="sakit_{{ $student->student_id }}" value="2" autocomplete="off">
                                    <label class="btn btn-outline-warning btn-sm" for="sakit_{{ $student->student_id }}">Sakit</label>

                                    <input type="radio" class="btn-check" name="status[{{ $student->student_id }}]" id="alpha_{{ $student->student_id }}" value="3" autocomplete="off">
                                    <label class="btn btn-outline-secondary btn-sm" for="alpha_{{ $student->student_id }}">Alpha</label>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-success mt-3">Save Presence</button>
        </form>
    </div>

    <!-- Tambahkan gaya tombol aktif -->
    <style>
        .status-all-btn.active {
            background-color: #0d6efd !important;
            color: white !important;
            border-color: #0a58ca !important;
        }
    </style>

    <!-- Script untuk Set All Status dan deteksi perubahan manual -->
    <script>
        let activeSetAllButton = null;

        function setAllStatus(value, button) {
            const allStudents = @json($students->pluck('student_id'));
            allStudents.forEach(id => {
                const radioId = {
                    0: `tidak_${id}`,
                    1: `hadir_${id}`,
                    2: `sakit_${id}`,
                    3: `alpha_${id}`
                }[value];
                const radioInput = document.getElementById(radioId);
                if (radioInput) {
                    radioInput.checked = true;
                }
            });

            // Set tombol aktif
            document.querySelectorAll('.status-all-btn').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            activeSetAllButton = button;
        }

        // Hapus tombol aktif jika ada perubahan status manual
        window.addEventListener('DOMContentLoaded', () => {
            const radios = document.querySelectorAll('input[type="radio"]');
            radios.forEach(radio => {
                radio.addEventListener('change', () => {
                    if (activeSetAllButton) {
                        activeSetAllButton.classList.remove('active');
                        activeSetAllButton = null;
                    }
                });
            });
        });
    </script>
@endsection
