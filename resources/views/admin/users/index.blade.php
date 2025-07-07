@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Users</h2>

    @foreach ($users as $user)
        <div class="border p-3 mb-2 d-flex justify-content-between align-items-center">
            <span>{{ $user->email }}</span>
            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Delete this user?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger">Delete</button>
            </form>
        </div>
    @endforeach

    {{ $users->links() }}
</div>
@endsection
