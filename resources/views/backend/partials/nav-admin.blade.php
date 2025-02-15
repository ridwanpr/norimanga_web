<ul class="nav nav-pills mt-4">
    <li class="nav-item">
        <a class="nav-link me-1 {{ Route::is('dashboard') ? 'active' : '' }}"
            href="{{ route('dashboard') }}">Dashboard</a>
    </li>
    <li class="nav-item">
        <a class="nav-link me-1 {{ Route::is('admin.users.index') ? 'active' : '' }}"
            href="{{ route('admin.users.index') }}">Manage Users</a>
    </li>
    <li class="nav-item">
        <a class="nav-link me-1 {{ Route::is('manage-comic.index') || Route::is('manage-comic.create') ? 'active' : '' }}"
            href="{{ route('manage-comic.index') }}">Manage Comics</a>
    </li>
    <li class="nav-item">
        <a class="nav-link me-1" href="#">Manage Chapters</a>
    </li>
    <li class="nav-item">
        <a class="nav-link me-1" href="#">Manage Projects</a>
    </li>
    <li class="nav-item ms-auto">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger">Logout</button>
        </form>
    </li>
</ul>
