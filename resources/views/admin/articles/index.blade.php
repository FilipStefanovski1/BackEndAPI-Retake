@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>All Articles</h2>

    {{-- ✅ Show success message --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('admin.articles.create') }}" class="btn btn-success mb-3">+ New Article</a>

    @foreach ($articles as $article)
        <div class="border p-3 mb-3 rounded">
            <h4>{{ $article->title }}</h4>
            <p><small>{{ $article->created_at->format('F j, Y') }}</small></p>
            <p>Status: <strong>{{ $article->published ? 'Published' : 'Draft' }}</strong></p>

            <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-sm btn-warning">Edit</a>

            <form action="{{ route('admin.articles.delete', $article->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this article?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger">Delete</button>
            </form>
        </div>
    @endforeach

    {{ $articles->links() }}
</div>
@endsection
