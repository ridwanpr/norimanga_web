<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand me-5 pe-4" href="{{ route('home') }}"
            style="font-family: 'Nunito', sans-serif;
               font-weight: 800;
               font-size: 2rem;
               color: #4CAF50;">
            Panelesia
        </a>
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="true"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/">
                       Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('manga.grid-list') }}">
                        Daftar Komik
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('bookmark.index') }}">
                        Bookmark
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link" href="{{ route('blog.index') }}">
                        Blog
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link" href="https://forms.gle/woMnsABJ4DJhsN1B9" target="_blank">
                        Lapor
                    </a>
                </li>
            </ul>
            @guest
                <div class="d-flex align-items-center mt-2 mt-md-0">
                    <a class="nav-link" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login/Register
                    </a>
                </div>
            @endguest
            @auth
                <div class="d-flex align-items-center mt-2 mt-md-0">
                    <a class="nav-link fw-bold"
                        href="{{ auth()->user()->role_id == 2 ? route('my-account') : route('dashboard') }}">
                        <i class="bi bi-person-circle me-2"></i><span class="text-primary">My</span>
                        {{ auth()->user()->role_id == 2 ? 'Account' : 'Dashboard' }}
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>
<div class="container mt-3">
    <form class="d-flex" role="search" action="{{ route('manga.grid-list') }}" method="GET">
        <input class="form-control me-2" type="search" name="search" placeholder="Cari Komik" aria-label="Search">
        <button class="btn btn-grey" type="submit" aria-label="Search" title="Search">
            <i class="bi bi-search"></i>
        </button>
    </form>
</div>
<hr>
