@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Admins</h2>

    @foreach ($admins as $admin)
        <div class="border p-3 mb-2">
            {{ $admin->email }}
        </div>
    @endforeach

    {{ $admins->links() }}
</div>
@endsection
