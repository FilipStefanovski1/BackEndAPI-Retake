@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Edit Article</h2>

    <form method="POST" action="{{ route('admin.articles.update', $article->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" value="{{ $article->title }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Content</label>
            <textarea name="content" rows="6" class="form-control" required>{{ $article->content }}</textarea>
        </div>

        <div class="mb-3">
            <label>Image (optional)</label>
            <input type="file" name="image" class="form-control">
            @if ($article->image)
                <img src="{{ asset('storage/' . $article->image) }}" class="img-fluid mt-2" width="200">
            @endif
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="published" class="form-check-input" value="1" {{ $article->published ? 'checked' : '' }}>
            <label class="form-check-label">Published</label>
        </div>

        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
