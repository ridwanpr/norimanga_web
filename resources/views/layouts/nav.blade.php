<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand me-5 pe-4" href="{{ route('home') }}">Nori</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">
                        <i class="bi bi-house-door me-2"></i>Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-list-columns me-2"></i>Project
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-book me-2"></i>Daftar Komik
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-eye-slash me-2"></i>NSFW 18+
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-bookmark-heart me-2"></i>Bookmark
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Lapor
                    </a>
                </li>
            </ul>
            <form class="d-flex me-3" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-grey" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
            <div class="d-flex align-items-center mt-2 mt-md-0">
                <a class="nav-link" href="#">
                    Login/Register
                </a>
            </div>
        </div>
    </div>
</nav>
