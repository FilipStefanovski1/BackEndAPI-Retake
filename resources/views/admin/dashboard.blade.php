@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Admin Dashboard – Articles Overview</h2>

    <!-- Stats -->
    <div class="mb-4">
        <strong>Total Articles:</strong> {{ $articleCount }}<br>
        <strong>Total Users:</strong> {{ $userCount }}<br>
        <strong>Total Admins:</strong> {{ $adminCount }}
    </div>

    <!-- Management Links -->
    <div class="mb-4 d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.users') }}" class="btn btn-outline-primary">View All Users</a>
        <a href="{{ route('admin.admins') }}" class="btn btn-outline-secondary">View All Admins</a>
        <a href="{{ route('admin.articles.create') }}" class="btn btn-success">Create New Article</a>
    </div>

    <!-- Article Table -->
    @if($articles->isEmpty())
        <div class="alert alert-info">No articles found.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Published</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($articles as $article)
                    <tr>
                        <td>{{ $article->title }}</td>
                        <td>
                            @if($article->published)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td>{{ $article->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('admin.articles.delete', $article->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this article?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
