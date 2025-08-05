@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Edit Article</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.articles.update', $article->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" value="{{ old('title', $article->title) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Content</label>
            <textarea name="content" rows="6" class="form-control" required>{{ old('content', $article->content) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Image (optional)</label>
            <input type="file" name="image" class="form-control">
            <div class="mt-2">
                <img src="{{ $article->image_url }}" class="img-fluid rounded" width="200" alt="Current Article Image">
            </div>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="published" class="form-check-input" value="1" {{ $article->published ? 'checked' : '' }}>
            <label class="form-check-label">Published</label>
        </div>

        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
