@extends('app_layouts.app')

@section('content')
    <x-back-button>Back</x-back-button>

    <div class="container">
        <h1>Academic Period Page</h1>


        <!-- Tabel Presence -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($periods as $period)
                    <tr>
                        <td>{{ $period->id }}</td>
                        <td>{{ $period->name }}</td>
                        @if($period->active == 1)
                        <td>{{ 'Active' }}</td>
                        @else
                        <td>{{ 'Not Active' }}</td>
                        @endif
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
