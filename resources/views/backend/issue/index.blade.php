@extends('layouts.app')

@section('content')
    <div class="container">
        @include('backend.partials.nav-admin')

        <div class="card shadow-sm mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Reported Issue</span>
                <form method="GET" action="{{ route('backend.user-issue.index') }}">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All</option>
                        <option value="unsolved" {{ request('status') === 'unsolved' ? 'selected' : '' }}>Unsolved</option>
                        <option value="solved" {{ request('status') === 'solved' ? 'selected' : '' }}>Solved</option>
                    </select>
                </form>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>URL</th>
                                <th>Created At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($userIssue as $issue)
                                <tr>
                                    <td>{{ ($userIssue->currentPage() - 1) * $userIssue->perPage() + $loop->iteration }}
                                    </td>
                                    <td>{{ $issue->url }}</td>
                                    <td>{{ $issue->created_at }}</td>
                                    <td>
                                        @if (!$issue->is_solved)
                                            <span class="badge bg-danger">Unsolved</span>
                                        @else
                                            <span class="badge bg-success">Solved</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (!$issue->is_solved)
                                            <button class="btn btn-sm btn-primary solve-btn"
                                                data-id="{{ $issue->id }}">Mark as Solved</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $userIssue->appends(['status' => request('status')])->links() }}
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        document.querySelectorAll(".solve-btn").forEach(button => {
            button.addEventListener("click", function() {
                let issueId = this.getAttribute("data-id");
                let row = this.closest("tr");

                if (confirm("Yakin ingin menandai sebagai Solved?")) {
                    fetch("{{ route('backend.user-issue.solve') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                id: issueId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                row.querySelector("td:nth-child(4)").innerHTML =
                                    '<span class="badge bg-success">Solved</span>';
                                row.querySelector(".solve-btn").remove();
                            } else {
                                alert("Gagal memperbarui status.");
                            }
                        })
                        .catch(() => alert("Terjadi kesalahan, coba lagi."));
                }
            });
        });
    </script>
@endpush
