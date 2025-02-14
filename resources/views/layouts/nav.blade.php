<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand me-5 pe-4" href="{{ route('home') }}"
            style="font-family: 'Nunito', sans-serif;
               font-weight: 800;
               font-size: 2rem;
               color: #1e88e5;">
            Nori
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">
                        <i class="bi bi-house-fill me-2"></i>Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-collection-fill me-2 text-primary"></i>Project
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-journal-bookmark-fill me-2 text-warning"></i>Daftar Komik
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-explicit-fill me-2 text-danger"></i>NSFW 18+
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-bookmark-star-fill me-2 text-success"></i>Bookmark
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-flag-fill me-2 text-info"></i>Lapor
                    </a>
                </li>
            </ul>
            <div class="d-flex align-items-center mt-2 mt-md-0">
                <a class="nav-link" href="#">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login/Register
                </a>
            </div>
        </div>
    </div>
</nav>
<div class="container my-3">
    <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-grey" type="submit">
            <i class="bi bi-search"></i>
        </button>
    </form>
</div>
