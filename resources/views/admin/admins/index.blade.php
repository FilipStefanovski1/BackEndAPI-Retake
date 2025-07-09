@extends('layouts.app')

@section('content')
<div class="container py-4">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary mb-3">← Back to Dashboard</a>
    <h2 class="mb-4">All Admins</h2>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Email</th>
                <th>Joined</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($admins as $index => $admin)
                <tr>
                    <td>{{ $admins->firstItem() + $index }}</td>
                    <td>{{ $admin->email }}</td>
                    <td>{{ $admin->created_at->format('M d, Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No admins found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $admins->links() }}
</div>
@endsection
