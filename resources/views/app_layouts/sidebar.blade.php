<div class="bg-dark text-white p-3 sidebar" id="sidebar">
    <h4>Simba-App</h4>
    <h5>@if(Auth::check()) {{ Auth::user()->name }} @else Guest @endif</h5>
    <ul class="nav flex-column">
        <li class="nav-item"><a href="{{ route('schedule.index') }}" class="nav-link text-white">Schedule</a></li>
{{--        <li class="nav-item"><a href="{{ route('dash') }}" class="nav-link text-white">Dashboard</a></li>--}}
{{--        <li class="nav-item"><a href="{{ route('example') }}" class="nav-link text-white">Example</a></li>--}}
{{--        <li class="nav-item"><a href="{{ route('lecturer.index') }}" class="nav-link text-white">Lecturer</a></li>--}}
{{--        <li class="nav-item"><a href="{{ route('period.index') }}" class="nav-link text-white">Academic Period</a></li>--}}
{{--        <li class="nav-item"><a href="{{ route('enrollment.index') }}" class="nav-link text-white">Enrollment</a></li>--}}
        <!-- Logout Button -->
        <li class="nav-item mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger w-100">
                    Logout
                </button>
            </form>
        </li>
    </ul>
</div>
