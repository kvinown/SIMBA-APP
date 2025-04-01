@extends('app_layouts.app')

@section('content')
    <div class="container">
        <h1>Lecturer Page</h1>

        <!-- Informasi Period, Course, dll -->
{{--        <div class="card mb-4">--}}
{{--            <div class="card-body">--}}
{{--                <table class="table table-borderless">--}}
{{--                    <tbody>--}}
{{--                    <tr>--}}
{{--                        <td><strong>Period:</strong></td>--}}
{{--                        <td>2024</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td><strong>Course:</strong></td>--}}
{{--                        <td>Web Development</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td><strong>Class and Type:</strong></td>--}}
{{--                        <td>A - Theory</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td><strong>Schedule:</strong></td>--}}
{{--                        <td>Monday, 10:00 AM - 12:00 PM</td>--}}
{{--                    </tr>--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}


        <!-- Tabel Presence -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                <tr>
                    <th>NIK</th>
                    <th>NIDN</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Department</th>
                    <th>Role</th>
                    <th>Faculty</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($lecturers as $lecturer)
                    <tr>
                        <td>{{ $lecturer->nik }}</td>
                        <td>{{ $lecturer->nidn }}</td>
                        <td>{{ $lecturer->name }}</td>
                        <td>{{ $lecturer->email }}</td>
                        <td>{{ $lecturer->status }}</td>
                        <td>{{ $lecturer->department->name }}</td>
                        <td>{{ $lecturer->role->name }}</td>
                        <td>{{ $lecturer->department->faculty->name }}</td>
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
