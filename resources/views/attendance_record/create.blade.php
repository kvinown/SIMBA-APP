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

        <!-- Presence Form -->
        <form method="POST" action="{{ route('schedule-detail.store') }}">
            @csrf

            <div class="card mb-4">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                        <!-- Course Info -->
                        <tr>
                            <td>
                                <label class="form-label"><strong>Course</strong></label>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-md">
                                        <input type="text" class="form-control" value="{{ $schedule->course->id }}" disabled>
                                    </div>
                                    <div class="col-md">
                                        <input type="text" class="form-control" value="{{ $schedule->course->name }}" disabled>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- Class & Type -->
                        <tr>
                            <td>
                                <label class="form-label"><strong>Class</strong></label>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-md">
                                        <input type="text" class="form-control" value="{{ $schedule->course_class }}" disabled>
                                    </div>
                                    <div class="col-md">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label class="form-label"><strong>Type: </strong></label>
                                            </div>
                                            <div class="col-md">
                                                <input type="text" class="form-control " value="{{ $schedule->type }}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- Inputs -->
                        <tr>
                            <td><label for="week_num" class="form-label"><strong>Week</strong></label></td>
                            <td><input type="number" id="week_num" name="week_num" class="form-control" value="{{ old('week_num', $nextWeekNum) }}" required></td>
                        </tr>
                        <tr>
                            <td><label for="schedule_date" class="form-label"><strong>Input Date</strong></label></td>
                            <td><input type="date" id="schedule_date" name="schedule_date" class="form-control" value="{{ date('Y-m-d') }}" required></td>
                        </tr>
                        <tr>
                            <td><label for="course_start_time" class="form-label"><strong>Start Time</strong></label></td>
                            <td><input type="time" id="course_start_time" name="course_start_time" class="form-control" value="{{ old('course_start_time', '08:00') }}" required></td>
                        </tr>
                        <tr>
                            <td><label for="course_end_time" class="form-label"><strong>End Time</strong></label></td>
                            <td><input type="time" id="course_end_time" name="course_end_time" class="form-control" value="{{ old('course_end_time', '10:00') }}" required></td>
                        </tr>
                        <tr>
                            <td><label class="form-label"><strong>Student</strong></label></td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#studentModal">
                                    Show Attendance Table
                                </button>
                            </td>
                        </tr>
                        <!-- Student Count Row -->
                        <tr>
                            <td><strong>Students Present:</strong></td>
                            <td>
                                <input type="number" id="presentCount" name="student_count" value="0" readonly class="form-control" style="width: 100px;">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="topic" class="form-label"><strong>Topic</strong></label></td>
                            <td><input type="text" id="topic" name="topic" class="form-control" required></td>
                        </tr>
                        <tr>
                            <td><label for="class_information" class="form-label"><strong>Class Information</strong></label></td>
                            <td><textarea id="class_information" name="class_information" class="form-control" required>{{ old('class_information', 'Introduction to the course') }}</textarea></td>
                        </tr>

                        <!-- Hidden Inputs -->
                        <input type="hidden" name="course_id" value="{{ $data['course_id'] }}">
                        <input type="hidden" name="lecturer_nik" value="{{ $data['lecturer_nik'] }}">
                        <input type="hidden" name="academic_period_id" value="{{ $data['academic_period_id'] }}">
                        <input type="hidden" name="course_class" value="{{ $data['course_class'] }}">
                        <input type="hidden" name="type" value="{{ $data['type'] }}">
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal for Attendance Table -->
            <div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="studentModalLabel">Attendance Table</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Bulk Status Buttons -->
                            <div class="mb-3">
                                <strong>Set All Status:</strong>
                                <button type="button" class="btn btn-outline-success btn-sm status-all-btn" onclick="setAllStatus(1, this)">Hadir</button>
                                <button type="button" class="btn btn-outline-danger btn-sm status-all-btn" onclick="setAllStatus(0, this)">Tidak Hadir</button>
                                <button type="button" class="btn btn-outline-warning btn-sm status-all-btn" onclick="setAllStatus(2, this)">Sakit</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm status-all-btn" onclick="setAllStatus(3, this)">Alpha</button>
                            </div>
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
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-success mt-3">Save Presence</button>
        </form>
    </div>

    <!-- Custom Styles -->
    <style>
        .status-all-btn.active {
            background-color: #0d6efd !important;
            color: white !important;
            border-color: #0a58ca !important;
        }
    </style>

    <!-- JS: Set All Status and Update Present Count -->
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

            // Highlight selected button
            document.querySelectorAll('.status-all-btn').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            activeSetAllButton = button;

            // Update count after set all
            updatePresentCount();
        }

        function updatePresentCount() {
            const checkedRadios = document.querySelectorAll('input[type="radio"]:checked[value="1"]');
            const count = checkedRadios.length;
            const presentCountInput = document.getElementById('presentCount');
            presentCountInput.value = count;
        }

        window.addEventListener('DOMContentLoaded', () => {
            updatePresentCount();

            const radios = document.querySelectorAll('input[type="radio"]');
            radios.forEach(radio => {
                radio.addEventListener('change', () => {
                    updatePresentCount();

                    if (activeSetAllButton) {
                        activeSetAllButton.classList.remove('active');
                        activeSetAllButton = null;
                    }
                });
            });
        });
    </script>
@endsection
