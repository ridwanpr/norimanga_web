<ul class="nav nav-pills mt-4">
    <li class="nav-item">
        <a class="nav-link me-1 {{ Route::is('dashboard') ? 'active' : '' }}"
            href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill"></i> Dashboard</a>
    </li>
    <li class="nav-item">
        <a class="nav-link me-1" href="{{ route('storage-status') }}"><i class="bi bi-file-earmark-text-fill"></i> Storage Status</a>
    </li>
    <li class="nav-item">
        <a class="nav-link me-1 {{ Route::is('admin.users.index') ? 'active' : '' }}"
            href="{{ route('admin.users.index') }}"><i class="bi bi-person-fill"></i> Manage Users</a>
    </li>
    <li class="nav-item">
        <a class="nav-link me-1 {{ Route::is('automation.index') ? 'active' : '' }}"
            href="{{ route('automation.index') }}"><i class="bi bi-robot-fill"></i> Automation</a>
    </li>
    <li class="nav-item">
        <a class="nav-link me-1 {{ Route::is('manage-comic.index') || Route::is('manage-comic.create') ? 'active' : '' }}"
            href="{{ route('manage-comic.index') }}"><i class="bi bi-book-fill"></i> Manage Comics</a>
    </li>
    {{-- <li class="nav-item">
        <a class="nav-link me-1" href="{{ route('manga-chapters.index') }}">Manage Chapters</a>
    </li>
    <li class="nav-item">
        <a class="nav-link me-1" href="#">Manage Projects</a>
    </li> --}}
    <li class="nav-item ms-auto">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger">Logout</button>
        </form>
    </li>
</ul>
