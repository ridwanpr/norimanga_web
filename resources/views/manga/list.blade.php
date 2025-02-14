@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <h1 class="fs-5 mb-3 fw-bold text-white">Daftar Komik</h1>

        <!-- Alphabet Navigation -->
        <div class="d-flex flex-wrap justify-content-center gap-1 mb-3">
            <a href="#num" class="btn btn-sm btn-outline-secondary text-white px-2">#</a>
            @foreach (range('A', 'Z') as $letter)
                <a href="#{{ $letter }}"
                    class="btn btn-sm btn-outline-secondary text-white px-2">{{ $letter }}</a>
            @endforeach
        </div>

        <!-- Comic List -->
        @php
            $comics = [
                '#' => ['20th Century Boys', '7 Seeds', '91 Days'],
                'A' => ['Attack on Titan', 'Akame ga Kill', 'A Silent Voice', 'Another', 'Assassination Classroom'],
                'B' => ['Bleach', 'Berserk', 'Black Clover', 'Blue Exorcist', 'Bungo Stray Dogs'],
                'C' => ['Chainsaw Man', 'Claymore', 'Code Geass', 'Conan'],
                'D' => ['Demon Slayer', 'Death Note', 'Dr. Stone'],
                'E' => ['Evangelion'],
                'F' => ['Fairy Tail', 'Fullmetal Alchemist'],
                'G' => ['Gantz', 'Gintama', 'Great Teacher Onizuka'],
                'H' => ['Haikyuu!', 'Himouto! Umaru-chan'],
                'I' => ['Inuyasha', 'Is It Wrong to Try to Pick Up Girls in a Dungeon?'],
                'J' => ['Jojo\'s Bizarre Adventure', 'Jujutsu Kaisen'],
                'K' => ['Kaguya-sama: Love is War', 'Kaiju No. 8', 'Kimi no Todoke'],
                'L' => ['Liar Game', 'Little Witch Academia'],
                'M' => ['Made in Abyss', 'Magi: The Labyrinth of Magic', 'Monster'],
                'N' => ['Naruto', 'Nisekoi', 'No Game No Life'],
                'O' => ['One Piece', 'One Punch Man', 'Overlord'],
                'P' => ['Parasyte', 'Psycho-Pass'],
                'Q' => ['Quintessential Quintuplets'],
                'R' => ['Re:Zero', 'Rurouni Kenshin'],
                'S' => ['Sword Art Online', 'Samurai Champloo'],
                'T' => ['Tokyo Ghoul', 'Toradora!'],
                'U' => ['Uchuu Kyoudai'],
                'V' => ['Vagabond'],
                'W' => ['Welcome to the N.H.K.'],
                'X' => ['X/1999'],
                'Y' => ['Yakusoku no Neverland'],
                'Z' => ['Zetman'],
            ];
        @endphp

        <div class="comic-list">
            @foreach ($comics as $letter => $titles)
                <div id="{{ $letter }}" class="mt-3">
                    <h2 class="fs-6 fw-bold border-bottom pb-1 text-white">{{ $letter }}</h2>
                    <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-1">
                        @foreach ($titles as $comic)
                            <div class="col">
                                <a href="#" class="text-decoration-none d-block small p-1 border rounded text-white">
                                    {{ $comic }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".btn-outline-secondary").forEach(button => {
                button.addEventListener("click", function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute("href").substring(1);
                    const targetElement = document.getElementById(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 50,
                            behavior: "smooth"
                        });
                    }
                });
            });
        });
    </script>
@endpush
