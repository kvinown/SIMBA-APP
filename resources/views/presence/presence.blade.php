@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Presence Page</h1>

        <!-- Informasi Period, Course, dll -->
        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-borderless">
                    <tbody>
                    <tr>
                        <td><strong>Period:</strong></td>
                        <td>2024</td>
                    </tr>
                    <tr>
                        <td><strong>Course:</strong></td>
                        <td>Web Development</td>
                    </tr>
                    <tr>
                        <td><strong>Class and Type:</strong></td>
                        <td>A - Theory</td>
                    </tr>
                    <tr>
                        <td><strong>Schedule:</strong></td>
                        <td>Monday, 10:00 AM - 12:00 PM</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tombol Add Presence -->
        <div class="mb-3">
            <a class="btn btn-primary" href="{{ route('presence_create') }}">+ Add Presence</a>
        </div>

        <!-- Tabel Presence -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                <tr>
                    <th>Week</th>
                    <th>Lecturer Material (Topic)</th>
                    <th>Details</th>
                    <th>Class Information</th>
                    <th>File</th>
                    <th>Input Date</th>
                    <th>Detail</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @php
                    // Dummy data untuk testing
                    $presences = [
                        ['week' => 1, 'topic' => 'HTML Basics', 'details' => 'Introduction to HTML', 'class_info' => 'Room 101', 'input_date' => '2025-03-20', 'checked' => true],
                        ['week' => 2, 'topic' => 'CSS Basics', 'details' => 'Styling with CSS', 'class_info' => 'Room 102', 'input_date' => '2025-03-27', 'checked' => false],
                        ['week' => 3, 'topic' => 'JavaScript Basics', 'details' => 'Introduction to JS', 'class_info' => 'Room 103', 'input_date' => '2025-04-03', 'checked' => false],
                    ];
                @endphp

                @foreach ($presences as $presence)
                    <tr>
                        <td>{{ $presence['week'] }}</td>
                        <td>{{ $presence['topic'] }}</td>
                        <td>{{ $presence['details'] }}</td>
                        <td>{{ $presence['class_info'] }}</td>
                        <td><a href="#">Download</a></td>
                        <td>{{ $presence['input_date'] }}</td>
                        <td>
                            <a class="btn btn-sm btn-info" href="{{ route('presence_detail') }}">Detail</a>
                        </td>
                        <td>
                            @if ($presence['checked'])
                                <span class="badge bg-success">Checked</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                        @if (!$presence['checked'])
                            <td>
                                <button class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
