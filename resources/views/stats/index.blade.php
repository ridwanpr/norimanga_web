<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Membaca</title>
    @vite('resources/css/app.css')
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
            font-size: 13px;
        }

        .stat-card {
            background: #2d2d2d;
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .genre-tag {
            background: #4a4a4a;
            border-radius: 20px;
            padding: 8px 20px;
            margin: 5px;
        }

        .chart-bar {
            height: 200px;
            width: 40px;
            background: #4a4a4a;
            border-radius: 5px;
            position: relative;
        }

        .chart-fill {
            position: absolute;
            bottom: 0;
            width: 100%;
            background: #8a2be2;
            border-radius: 5px;
            height: 70%;
        }

        .highlight {
            color: #1e88e5;
        }

        @media (max-width: 768px) {
            .stat-card {
                margin-bottom: 0.75rem;
            }
        }
    </style>
</head>

<body>
    <header class="container py-4">
        <h1 class="display-4 fw-bold">Statistik &bull; User Name</h1>
        <p class="lead mb-0">https://nori.my/stats/user_slug</p>
    </header>

    <main class="container">
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="stat-card h-100">
                    <h3>Total Chapters</h3>
                    <p class="display-2 fw-bold highlight mb-1">1,234</p>
                    <small>30 hari terakhir: 234 chapters</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card h-100">
                    <h3>Total Judul</h3>
                    <p class="display-2 fw-bold highlight mb-1">89</p>
                    <small>12 judul baru bulan ini</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card h-100">
                    <h3>Hari Membaca</h3>
                    <p class="display-2 fw-bold highlight mb-1">287</p>
                    <small>Rata-rata 5.2 jam per hari</small>
                </div>
            </div>
        </div>

        <div class="stat-card mb-3">
            <h2 class="mb-3">Genre Favorit</h2>
            <div class="d-flex flex-wrap mb-2">
                <div class="genre-tag">Aksi <span class="badge bg-purple">35%</span></div>
                <div class="genre-tag">Romansa <span class="badge bg-purple">28%</span></div>
                <div class="genre-tag">Fantasi <span class="badge bg-purple">22%</span></div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <div class="stat-card h-100">
                    <h3 class="mb-3">Judul Teratas</h3>
                    <div class="list-group">
                        <div class="list-group-item bg-dark border-secondary">
                            1. Solo Leveling <span class="float-end">142 chapters</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stat-card h-100">
                    <h3 class="mb-3">Penulis Favorit</h3>
                    <div class="list-group">
                        <div class="list-group-item bg-dark border-secondary">
                            Kim Jung-ji <span class="float-end">8 judul</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <h2 class="mb-3">Riwayat Baca</h2>
            <div class="list-group">
                <div class="list-group-item bg-dark border-secondary">
                    <div class="d-flex justify-content-between">
                        <span>Membaca chapter 145 dari Solo Leveling</span>
                        <small>2 jam yang lalu</small>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
