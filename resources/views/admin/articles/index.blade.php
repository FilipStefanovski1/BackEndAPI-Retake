@extends('layouts.app')

@push('styles')
<style>
    .card:hover {
        box-shadow: 0 8px 18px rgba(0,0,0,0.1);
        transform: translateY(-3px);
        transition: all 0.3s ease;
    }
    .featured-img {
        object-fit: cover;
        height: 250px;
        width: 100%;
    }
    .object-fit-cover {
        object-fit: cover;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Main Column -->
        <div class="col-md-8">
            <h4 class="mb-4">All Articles</h4>

            <!-- Search Bar -->
            <form action="{{ route('home') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search articles...">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
            </form>

            <!-- Articles Section -->
            @if($articles->count())
                @php $featured = $articles->first(); @endphp
                <div class="card mb-5 shadow-sm">
                    <img src="{{ $featured->image && file_exists(public_path('storage/' . $featured->image)) 
                        ? asset('storage/' . $featured->image) 
                        : 'https://via.placeholder.com/1000x400?text=No+Image' }}" 
                        class="featured-img rounded-top" alt="{{ $featured->title }}">
                    <div class="card-body">
                        <span class="badge bg-danger mb-2">Featured</span>
                        <h2 class="card-title">{{ $featured->title }}</h2>
                        <p class="text-muted small">{{ $featured->created_at->format('F j, Y') }}</p>
                        <p class="card-text">{{ \Illuminate\Support\Str::limit(strip_tags($featured->content), 150) }}</p>
                        <a href="{{ route('articles.show', $featured->id) }}" class="btn btn-primary">Read More</a>
                    </div>
                </div>

                <!-- List Remaining Articles -->
                <div class="row">
                    @foreach($articles->skip(1) as $article)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <img src="{{ $article->image && file_exists(public_path('storage/' . $article->image)) 
                                    ? asset('storage/' . $article->image) 
                                    : 'https://via.placeholder.com/600x300?text=No+Image' }}" 
                                    class="card-img-top object-fit-cover" style="height: 180px;" alt="{{ $article->title }}">
                                <div class="card-body">
                                    <span class="badge bg-secondary mb-2">News</span>
                                    <h5 class="card-title">{{ $article->title }}</h5>
                                    <p class="text-muted small">{{ $article->created_at->format('F j, Y') }}</p>
                                    <p class="card-text">{{ \Illuminate\Support\Str::limit(strip_tags($article->content), 100) }}</p>
                                    <a href="{{ route('articles.show', $article->id) }}" class="btn btn-sm btn-outline-primary">Read More</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p>No articles found.</p>
            @endif

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $articles->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="position-sticky" style="top: 90px;">
                <h5 class="mb-4">Latest Articles</h5>
                <ul class="list-unstyled">
                    @foreach($latestArticles as $latest)
                        <li class="mb-3 border-bottom pb-2">
                            <a href="{{ route('articles.show', $latest->id) }}" class="fw-semibold text-dark text-decoration-none d-block">
                                {{ $latest->title }}
                            </a>
                            <small class="text-muted">{{ $latest->created_at->format('M d, Y') }}</small>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
