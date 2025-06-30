@extends('app_layouts.app')

@section('content')
    {{-- Menampilkan error validasi dari server jika ada --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <x-back-button>Back</x-back-button>

    <div class="container">
        <h1>Presence Store Page</h1>

        {{-- Panel Peringatan Mahasiswa Berisiko SEKARANG SUDAH DIPINDAHKAN KE DALAM MODAL --}}

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
                                <input type="text" class="form-control" value="{{ $schedule->course_class }}" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="form-label mb-0"><strong>Type:</strong></label>
                            </td>
                            <td>
                                <input type="text" class="form-control" value="{{ $schedule->type }}" disabled style="max-width: 200px;">
                            </td>
                        </tr>

                        <!-- Inputs -->
                        <tr>
                            <td><label for="week_num" class="form-label"><strong>Week</strong></label></td>
                            <td>
                                @php
                                    $selectedWeek = old('week_num', $nextWeekNum);
                                @endphp
                                <select id="week_num" name="week_num" class="form-control" required>
                                    @for ($i = 1; $i <= 19; $i++)
                                        @php
                                            $weekType = '';
                                            if ($i >= 1 && $i <= 14) {
                                                $weekType = 'Pertemuan Reguler';
                                            } elseif ($i == 15) {
                                                $weekType = 'UTS';
                                            } elseif ($i == 16) {
                                                $weekType = 'UAS';
                                            } else {
                                                $weekType = 'Susulan/Perbaikan';
                                            }
                                        @endphp
                                        <option value="{{ $i }}" {{ $selectedWeek == $i ? 'selected' : '' }}>
                                            {{ $i }} - {{ $weekType }}
                                        </option>
                                    @endfor
                                </select>
                                <div id="week-validation-message" class="text-danger small mt-1 fw-bold"></div>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="schedule_date" class="form-label"><strong>Input Date</strong></label></td>
                            <td><input type="date" id="schedule_date" name="schedule_date" class="form-control" value="{{ old('schedule_date', date('Y-m-d')) }}" required></td>
                        </tr>
                        <tr>
                            <td><label for="course_start_time" class="form-label"><strong>Start Time</strong></label></td>
                            <td>
                                <input type="time" id="course_start_time" name="course_start_time" class="form-control"
                                       value="{{ old('course_start_time', substr($schedule->start_time, 0, 5)) }}" required>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="course_end_time" class="form-label"><strong>End Time</strong></label></td>
                            <td>
                                <input type="time" id="course_end_time" name="course_end_time" class="form-control"
                                       value="{{ old('course_end_time', substr($schedule->end_time, 0, 5)) }}" required>
                            </td>
                        </tr>
                        <tr>
                            <td><label class="form-label"><strong>Student</strong></label></td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#studentModal">
                                    Show Students Table
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Students Present:</strong></td>
                            <td>
                                <input type="number" id="presentCount" name="student_count" value="{{ old('student_count', 0) }}" readonly class="form-control" style="width: 100px;">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="topic" class="form-label"><strong>Topic</strong></label></td>
                            <td><input type="text" id="topic" name="topic" class="form-control" required value="{{ old('topic') }}" placeholder="Contoh: Perkenalan dan Kontrak Kuliah"></td>
                        </tr>
                        <tr>
                            <td><label for="class_information" class="form-label"><strong>Class Information</strong></label></td>
                            <td>
                            <textarea id="class_information"
                                      name="class_information"
                                      class="form-control"
                                      required
                                      placeholder="Contoh: Morning, Latihan Assignment, Onsite/Online">{{ old('class_information') }}</textarea>
                            </td>
                        </tr>

                        <input type="hidden" id="course_id" name="course_id" value="{{ $data['course_id'] }}">
                        <input type="hidden" id="lecturer_nik" name="lecturer_nik" value="{{ $data['lecturer_nik'] }}">
                        <input type="hidden" id="academic_period_id" name="academic_period_id" value="{{ $data['academic_period_id'] }}">
                        <input type="hidden" id="course_class" name="course_class" value="{{ $data['course_class'] }}">
                        <input type="hidden" id="type" name="type" value="{{ $data['type'] }}">
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- Submit Button -->
            <button type="submit" class="btn btn-success mt-3">Save Presence</button>

            <!-- Modal for Attendance Table -->
            <div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="studentModalLabel">Attendance Table</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <!-- REVISI: Panel Peringatan Mahasiswa Berisiko dipindahkan ke sini -->
                            @if (!empty($riskyStudents['warning']) || !empty($riskyStudents['cekal']))
                                <div class="alert alert-warning mb-4" role="alert">
                                    <h4 class="alert-heading">Perhatian: Ringkasan Kehadiran Kritis</h4>
                                    <p>Berikut adalah daftar mahasiswa yang memerlukan perhatian khusus terkait persentase kehadiran.</p>
                                    <hr>

                                    {{-- Tabel Mahasiswa Warning --}}
                                    @if (!empty($riskyStudents['warning']))
                                        <strong>Di Ambang Batas (Jika absen hari ini, kehadiran akan &lt; 75%):</strong>
                                        <div class="table-responsive mt-2">
                                            <table class="table table-sm table-bordered mb-3 bg-white">
                                                <thead class="table-light">
                                                <tr>
                                                    <th>NRP</th>
                                                    <th>Nama</th>
                                                    <th>Kehadiran Saat Ini</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($riskyStudents['warning'] as $student)
                                                    <tr>
                                                        <td>{{ $student->student_id }}</td>
                                                        <td>{{ $student->student->name ?? '-' }}</td>
                                                        <td><strong>{{ $studentAttendanceData[$student->student_id]['percentage'] ?? 'N/A' }}%</strong></td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif

                                    {{-- Tabel Mahasiswa Cekal --}}
                                    @if (!empty($riskyStudents['cekal']))
                                        <strong class="text-danger">Sudah di Bawah 75% (Cekal):</strong>
                                        <div class="table-responsive mt-2">
                                            <table class="table table-sm table-bordered bg-white">
                                                <thead class="table-light">
                                                <tr>
                                                    <th>NRP</th>
                                                    <th>Nama</th>
                                                    <th>Kehadiran Saat Ini</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($riskyStudents['cekal'] as $student)
                                                    <tr class="table-danger">
                                                        <td>{{ $student->student_id }}</td>
                                                        <td>{{ $student->student->name ?? '-' }}</td>
                                                        <td><strong>{{ $studentAttendanceData[$student->student_id]['percentage'] ?? 'N/A' }}%</strong></td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            <!-- AKHIR REVISI -->

                            <div class="row mb-3 align-items-center">
                                <div class="col-md-3">
                                    <strong>Set All Status:</strong>
                                </div>
                                <div class="col-md-9">
                                    <button type="button" class="btn btn-outline-success btn-sm status-all-btn" onclick="setAllStatus('1', this)">Hadir</button>
                                    <button type="button" class="btn btn-outline-danger btn-sm status-all-btn" onclick="setAllStatus('0', this)">Tidak Hadir</button>
                                    <button type="button" class="btn btn-outline-warning btn-sm status-all-btn" onclick="setAllStatus('2', this)">Sakit</button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm status-all-btn" onclick="setAllStatus('3', this)">Izin</button>
                                </div>
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
                                        @php
                                            $statusClass = '';
                                            $studentStatus = $studentAttendanceData[$student->student_id]['status'] ?? 'safe';
                                            if ($studentStatus === 'warning') {
                                                $statusClass = 'table-warning';
                                            } elseif ($studentStatus === 'cekal') {
                                                $statusClass = 'table-danger';
                                            }
                                        @endphp
                                        <tr class="{{ $statusClass }}">
                                            <td>{{ $student->student_id }}</td>
                                            <td>{{ $student->student->name ?? '-' }}</td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Status Kehadiran">
                                                    <input type="radio" class="btn-check" name="status[{{ $student->student_id }}]" id="hadir_{{ $student->student_id }}" value="1" autocomplete="off"
                                                        {{ old('status.' . $student->student_id) == '1' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-success btn-sm" for="hadir_{{ $student->student_id }}">Hadir</label>

                                                    <input type="radio" class="btn-check" name="status[{{ $student->student_id }}]" id="tidak_{{ $student->student_id }}" value="0" autocomplete="off"
                                                        {{ old('status.' . $student->student_id) == '0' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-danger btn-sm" for="tidak_{{ $student->student_id }}">Tidak Hadir</label>

                                                    <input type="radio" class="btn-check" name="status[{{ $student->student_id }}]" id="sakit_{{ $student->student_id }}" value="2" autocomplete="off"
                                                        {{ old('status.' . $student->student_id) == '2' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-warning btn-sm" for="sakit_{{ $student->student_id }}">Sakit</label>

                                                    <input type="radio" class="btn-check" name="status[{{ $student->student_id }}]" id="izin_{{ $student->student_id }}" value="3" autocomplete="off"
                                                        {{ old('status.' . $student->student_id) == '3' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-secondary btn-sm" for="izin_{{ $student->student_id }}">Izin</label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Done</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <!-- JS: Set All Status and Update Present Count -->
    <script>
        let activeSetAllButton = null;

        function setAllStatus(value, button) {
            const radioButtons = document.querySelectorAll('#studentModal input[type="radio"]');
            radioButtons.forEach(radio => {
                if (radio.value === value) {
                    radio.checked = true;
                }
            });

            if(activeSetAllButton) {
                activeSetAllButton.classList.remove('active');
            }
            button.classList.add('active');
            activeSetAllButton = button;
            updatePresentCount();
        }

        function updatePresentCount() {
            const checkedRadios = document.querySelectorAll('#studentModal input[type="radio"]:checked');
            let count = 0;
            checkedRadios.forEach(radio => {
                if (radio.value === "1" || radio.value === "2" || radio.value === "3") {
                    count++;
                }
            });
            document.getElementById('presentCount').value = count;
        }

        document.addEventListener('DOMContentLoaded', () => {
            updatePresentCount();
            const radios = document.querySelectorAll('#studentModal input[type="radio"]');
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

    <!-- Script AJAX untuk validasi minggu -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const weekSelect = document.getElementById('week_num');
            const validationMessageEl = document.getElementById('week-validation-message');
            const submitButton = document.querySelector('button[type="submit"]');

            const courseId = document.getElementById('course_id').value;
            const academicPeriodId = document.getElementById('academic_period_id').value;
            const courseClass = document.getElementById('course_class').value;
            const type = document.getElementById('type').value;

            async function checkWeekExistence(selectedWeek) {
                validationMessageEl.textContent = 'Memeriksa...';
                submitButton.disabled = true;

                const url = new URL('{{ route('attendance.checkWeek') }}');
                url.searchParams.append('week_num', selectedWeek);
                url.searchParams.append('course_id', courseId);
                url.searchParams.append('academic_period_id', academicPeriodId);
                url.searchParams.append('course_class', courseClass);
                url.searchParams.append('type', type);

                try {
                    const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                    const data = await response.json();

                    if (data.exists) {
                        validationMessageEl.textContent = 'Perhatian: Data presensi untuk minggu ini sudah pernah diinput.';
                        submitButton.disabled = true;
                    } else {
                        validationMessageEl.textContent = '';
                        submitButton.disabled = false;
                    }
                } catch (error) {
                    console.error('Error checking week existence:', error);
                    validationMessageEl.textContent = 'Gagal memvalidasi minggu. Periksa koneksi Anda.';
                    submitButton.disabled = false;
                }
            }

            weekSelect.addEventListener('change', function () { checkWeekExistence(this.value); });
            if (weekSelect.value) { checkWeekExistence(weekSelect.value); }
        });
    </script>
@endpush

@push('styles')
    <!-- Custom Styles -->
    <style>
        .status-all-btn.active {
            background-color: #0d6efd !important;
            color: white !important;
            border-color: #0a58ca !important;
        }
    </style>
@endpush
