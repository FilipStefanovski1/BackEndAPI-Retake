@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Create New Article</h2>

    <form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Content</label>
            <textarea name="content" rows="6" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Image (optional)</label>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="published" class="form-check-input" value="1">
            <label class="form-check-label">Publish now</label>
        </div>

        <button class="btn btn-primary">Create</button>
    </form>
</div>
@endsection
