<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $articles = Article::where('published', true)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        $latestArticles = Article::where('published', true)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('articles.index', compact('articles', 'search', 'latestArticles'));
    }

    public function show($id)
    {
        $article = Article::where('published', true)->findOrFail($id);

        $next = Article::where('id', '>', $article->id)->where('published', true)->first();
        $prev = Article::where('id', '<', $article->id)->where('published', true)->orderBy('id', 'desc')->first();

        return view('articles.show', compact('article', 'next', 'prev'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'published' => 'nullable|boolean',
        ]);

        $article = new Article($request->only(['title', 'content', 'published']));

        if ($request->hasFile('image')) {
            $filename = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('articles', $filename, 'public');
            $article->image = 'articles/' . $filename;
        }

        $article->save();

        return redirect()->route('admin.articles.index')->with('success', 'Article created successfully!');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'published' => 'nullable|boolean',
        ]);

        $article->fill($request->only(['title', 'content', 'published']));

        if ($request->hasFile('image')) {
            // Delete old image
            if ($article->image && Storage::disk('public')->exists($article->image)) {
                Storage::disk('public')->delete($article->image);
            }

            $filename = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('articles', $filename, 'public');
            $article->image = 'articles/' . $filename;
        }

        $article->save();

        return redirect()->route('admin.articles.index')->with('success', 'Article updated successfully!');
    }
}
