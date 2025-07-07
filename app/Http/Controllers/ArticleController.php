<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $articles = Article::where('published', true)
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        $latestArticles = Article::where('published', true)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('articles.index', compact('articles', 'latestArticles', 'search'));
    }

    public function show($id)
    {
        $article = Article::where('published', true)->findOrFail($id);

        $next = Article::where('id', '>', $article->id)->where('published', true)->first();
        $prev = Article::where('id', '<', $article->id)->where('published', true)->orderBy('id', 'desc')->first();

        return view('articles.show', compact('article', 'next', 'prev'));
    }
}
