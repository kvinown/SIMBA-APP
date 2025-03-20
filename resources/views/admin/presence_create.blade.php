@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Presence Store Page</h1>

        <!-- Form Presence -->
        <form method="POST">
            @csrf
            <div class="card mb-4">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                        <tr>
                            <td><label for="week" class="form-label"><strong>Week:</strong></label></td>
                            <td><input type="number" id="week" class="form-control" name="week" required></td>
                        </tr>
                        <tr>
                            <td><label for="topic" class="form-label"><strong>Topic:</strong></label></td>
                            <td><input type="text" id="topic" class="form-control" name="topic" required></td>
                        </tr>
                        <tr>
                            <td><label for="input_date" class="form-label"><strong>Input Date:</strong></label></td>
                            <td><input type="date" id="input_date" class="form-control" name="input_date" value="{{ date('Y-m-d') }}" required></td>
                        </tr>
                        <tr>
                            <td><label for="students_count" class="form-label"><strong>Students Count:</strong></label></td>
                            <td><input type="number" id="students_count" class="form-control" name="students_count" required></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
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
                    <tr>
                        <td>123456</td>
                        <td>John Doe</td>
                        <td>
                            <select class="form-select">
                                <option value="Attend">Attend</option>
                                <option value="Absent">Absent</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>789012</td>
                        <td>Jane Doe</td>
                        <td>
                            <select class="form-select">
                                <option value="Attend">Attend</option>
                                <option value="Absent">Absent</option>
                            </select>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-success mt-3">Save Presence</button>
        </form>
    </div>
@endsection
