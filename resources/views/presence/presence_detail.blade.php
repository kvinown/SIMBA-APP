@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Presence Detail Page</h1>

        <!-- Informasi Presence -->
        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-borderless">
                    <tbody>
                    <tr>
                        <td><strong>Week:</strong></td>
                        <td>3</td>
                    </tr>
                    <tr>
                        <td><strong>Topic:</strong></td>
                        <td>CSS Basics</td>
                    </tr>
                    <tr>
                        <td><strong>Input Date:</strong></td>
                        <td>2025-03-27</td>
                    </tr>
                    <tr>
                        <td><strong>Students Count:</strong></td>
                        <td>30</td>
                    </tr>
                    <tr>
                        <td><strong>Attend:</strong></td>
                        <td>25</td>
                    </tr>
                    <tr>
                        <td><strong>Absence:</strong></td>
                        <td>5</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel Presence -->
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
                    <td><span class="badge bg-success">Attend</span></td>
                </tr>
                <tr>
                    <td>123457</td>
                    <td>Jane Doe</td>
                    <td><span class="badge bg-danger">Absent</span></td>
                </tr>
                <tr>
                    <td>123458</td>
                    <td>Michael Smith</td>
                    <td><span class="badge bg-success">Attend</span></td>
                </tr>
                <tr>
                    <td>123459</td>
                    <td>Alice Brown</td>
                    <td><span class="badge bg-danger">Absent</span></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
