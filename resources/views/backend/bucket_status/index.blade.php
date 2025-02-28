@extends('layouts.app')

@section('content')
    <div class="container">
        @include('backend.partials.nav-admin')

                <div class="container">
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                            <span>Storage Usage</span>
                            <i class="bi bi-hdd-rack fs-5"></i>
                        </div>
                        <div class="card-body">
                            @foreach ($bucketUsage as $usage)
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">{{ $usage['bucket_name'] }}</h6>
                                        <div class="text-muted small">
                                            {{ $usage['storage_used_gb'] }} / 16 GB
                                        </div>
                                    </div>
                                    <div class="progress" style="height: 10px;">
                                        @php
                                            $percent = floatval(str_replace('%', '', $usage['percent_usage']));
                                            $colorClass =
                                                $percent > 90
                                                    ? 'bg-danger'
                                                    : ($percent > 70
                                                        ? 'bg-warning'
                                                        : ($percent > 50
                                                            ? 'bg-info'
                                                            : 'bg-success'));
                                        @endphp
                                        <div class="progress-bar {{ $colorClass }}" role="progressbar"
                                            style="width: {{ $usage['percent_usage'] }}" aria-valuenow="{{ $percent }}"
                                            aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <div class="mt-2 small">
                                        <span class="text-muted">Used Space:</span> {{ $usage['storage_used_mb'] }}
                                        <span class="mx-2">|</span>
                                        <span class="text-muted">Usage:</span> {{ $usage['percent_usage'] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
