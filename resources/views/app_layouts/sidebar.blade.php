<div class="bg-dark text-white p-3 sidebar d-flex flex-column" id="sidebar">
    <h4>Simba-App</h4>
    <h5>@if(Auth::check()) {{ Auth::user()->name }} @else Guest @endif</h5>
    <ul class="nav flex-column flex-grow-1" >
        <li class="nav-item"><a href="{{ route('schedule.index') }}" class="btn btn-light w-100 my-1">Schedule</a></li>


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
