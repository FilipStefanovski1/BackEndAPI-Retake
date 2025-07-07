@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-9">
            {{-- Article Title --}}
            <h1 class="fw-bold display-5 mb-3" style="line-height: 1.3;">
                {{ $article->title }}
            </h1>

            {{-- Date & Reading Time --}}
            <div class="text-muted mb-4 fs-6">
                {{ $article->created_at->format('F j, Y') }} • 
                {{ ceil(str_word_count(strip_tags($article->content)) / 200) }} min read
            </div>

            {{-- Article Image --}}
            <div class="mb-4 text-center">
                @if ($article->image)
                    <img src="{{ asset('storage/' . $article->image) }}" class="img-fluid rounded shadow-sm" style="max-height: 500px; object-fit: cover;" alt="Article image">
                @else
                    <img src="https://via.placeholder.com/1000x400?text=No+Image" class="img-fluid rounded shadow-sm" alt="Placeholder image">
                @endif
            </div>

            {{-- Article Content --}}
            <div class="fs-5" style="line-height: 1.8;">
                {!! nl2br(e($article->content)) !!}
            </div>

            {{-- Article Navigation --}}
            <hr class="my-5">
            <div class="d-flex justify-content-between">
                @if ($prev)
                    <a href="{{ route('articles.show', $prev->id) }}" class="btn btn-outline-secondary">
                        ← {{ \Illuminate\Support\Str::limit($prev->title, 40) }}
                    </a>
                @else
                    <span></span>
                @endif

                @if ($next)
                    <a href="{{ route('articles.show', $next->id) }}" class="btn btn-outline-secondary ms-auto">
                        {{ \Illuminate\Support\Str::limit($next->title, 40) }} →
                    </a>
                @endif
            </div>
        </div>

        {{-- Optional Sidebar (blank for now) --}}
        <div class="col-lg-3 d-none d-lg-block ps-4">
            <h5 class="mb-3 border-bottom pb-2">Related Articles</h5>
            <p class="text-muted">Coming soon...</p>
        </div>
    </div>
</div>
@endsection
