<div class="bg-dark text-white p-3 sidebar" id="sidebar">
    <h4>My Admin</h4>
    <h5>@if(Auth::check()) {{ Auth::user()->name }} @else Guest @endif</h5>
    <ul class="nav flex-column">
        <li class="nav-item"><a href="{{route('dashboard')}}" class="nav-link text-white">Dashboard</a></li>
        <li class="nav-item"><a href="{{route('lecturer.index')}}" class="nav-link text-white">Lecturer</a></li>
        <li class="nav-item"><a href="#" class="nav-link text-white">Settings</a></li>
    </ul>
</div>
