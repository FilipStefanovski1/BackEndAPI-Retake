@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Admin Dashboard</h1>
    <div class="row">
        <div class="col-md-4">
            <a href="{{ route('admin.articles') }}" class="btn btn-primary w-100 mb-3">Manage Articles</a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.users') }}" class="btn btn-secondary w-100 mb-3">Manage Users</a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.admins') }}" class="btn btn-dark w-100 mb-3">View Admins</a>
        </div>
    </div>
</div>
@endsection
