@extends('layouts.app')

@section('content')
    <div class="container">
        @include('backend.partials.nav-admin')

        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card shadow-sm mt-4">
                    <div class="card-header">
                        Manga List
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Last Updated At</th>
                                        <th>Time Since Last Chapter</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($manga as $m)
                                        <tr>
                                            <td>{{ $m->title }}</td>
                                            <td>{{ $m->readable_date }}</td>
                                            <td>{{ $m->time_difference }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $manga->links() }}
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card shadow-sm mt-4">
                    <div class="card-header">
                        Comics Needing Updates
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="mangaUpdateAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="oneWeekHeader">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#oneWeekCollapse" aria-expanded="true"
                                        aria-controls="oneWeekCollapse">
                                        Not Updated for 1 Week
                                    </button>
                                </h2>
                                <div id="oneWeekCollapse" class="accordion-collapse collapse show"
                                    aria-labelledby="oneWeekHeader" data-bs-parent="#mangaUpdateAccordion">
                                    <div class="accordion-body">
                                        <ul class="list-group">
                                            @forelse ($mangaToUpdate['oneWeek'] as $manga)
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    {{ $manga->title }}
                                                    <span class="badge bg-warning rounded-pill">
                                                        {{ \Carbon\Carbon::parse($manga->updated_at)->diffForHumans() }}
                                                    </span>
                                                </li>
                                            @empty
                                                <li class="list-group-item text-muted">No comics need update</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="twoWeeksHeader">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#twoWeeksCollapse" aria-expanded="false"
                                        aria-controls="twoWeeksCollapse">
                                        Not Updated for 2 Weeks
                                    </button>
                                </h2>
                                <div id="twoWeeksCollapse" class="accordion-collapse collapse"
                                    aria-labelledby="twoWeeksHeader" data-bs-parent="#mangaUpdateAccordion">
                                    <div class="accordion-body">
                                        <ul class="list-group">
                                            @forelse ($mangaToUpdate['twoWeeks'] as $manga)
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    {{ $manga->title }}
                                                    <span class="badge bg-warning rounded-pill">
                                                        {{ \Carbon\Carbon::parse($manga->updated_at)->diffForHumans() }}
                                                    </span>
                                                </li>
                                            @empty
                                                <li class="list-group-item text-muted">No comics need update</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="threeWeeksHeader">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#threeWeeksCollapse" aria-expanded="false"
                                        aria-controls="threeWeeksCollapse">
                                        Not Updated for 3 Weeks
                                    </button>
                                </h2>
                                <div id="threeWeeksCollapse" class="accordion-collapse collapse"
                                    aria-labelledby="threeWeeksHeader" data-bs-parent="#mangaUpdateAccordion">
                                    <div class="accordion-body">
                                        <ul class="list-group">
                                            @forelse ($mangaToUpdate['threeWeeks'] as $manga)
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    {{ $manga->title }}
                                                    <span class="badge bg-warning rounded-pill">
                                                        {{ \Carbon\Carbon::parse($manga->updated_at)->diffForHumans() }}
                                                    </span>
                                                </li>
                                            @empty
                                                <li class="list-group-item text-muted">No comics need update</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="oneMonthHeader">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#oneMonthCollapse" aria-expanded="false"
                                        aria-controls="oneMonthCollapse">
                                        Not Updated for 1 Month
                                    </button>
                                </h2>
                                <div id="oneMonthCollapse" class="accordion-collapse collapse"
                                    aria-labelledby="oneMonthHeader" data-bs-parent="#mangaUpdateAccordion">
                                    <div class="accordion-body">
                                        <ul class="list-group">
                                            @forelse ($mangaToUpdate['oneMonth'] as $manga)
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    {{ $manga->title }}
                                                    <span class="badge bg-danger rounded-pill">
                                                        {{ \Carbon\Carbon::parse($manga->updated_at)->diffForHumans() }}
                                                    </span>
                                                </li>
                                            @empty
                                                <li class="list-group-item text-muted">No comics need update</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')

@endpush
