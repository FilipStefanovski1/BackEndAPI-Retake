<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Http\Middleware\IsAdmin;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', IsAdmin::class]);
    }

    // Admin Dashboard
    public function dashboard()
    {
        $articleCount = Article::count();
        $userCount = User::where('role', 'user')->count();
        $adminCount = User::where('role', 'admin')->count();
        $articles = Article::latest()->get();

        return view('admin.dashboard', compact('articleCount', 'userCount', 'adminCount', 'articles'));
    }

    // Admin: Article list
    public function articles()
    {
        $articles = Article::latest()->paginate(10);
        $latestArticles = Article::where('published', true)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.articles.index', compact('articles', 'latestArticles'));
    }

    // Admin: Show create article form
    public function createArticle()
    {
        return view('admin.articles.create');
    }

    // Admin: Store article
    public function storeArticle(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image',
            'published' => 'nullable|boolean'
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
        }

        Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $path,
            'published' => $request->has('published'),
        ]);

        return redirect()->route('admin.articles')->with('success', 'Article created.');
    }

    // Admin: Edit form
    public function editArticle($id)
    {
        $article = Article::findOrFail($id);
        return view('admin.articles.edit', compact('article'));
    }

    // Admin: Update article
    public function updateArticle(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image',
            'published' => 'nullable|boolean'
        ]);

        if ($request->hasFile('image')) {
            logger('Uploading new image: ' . $request->file('image')->getClientOriginalName());

            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }

            $article->image = $request->file('image')->store('articles', 'public');
        }

        $article->title = $request->title;
        $article->content = $request->content;
        $article->published = $request->has('published');

        $article->save();

        return redirect()->route('admin.articles')->with('success', 'Article updated.');
    }

    // Admin: Delete article
    public function deleteArticle($id)
    {
        $article = Article::findOrFail($id);

        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        return redirect()->route('admin.articles')->with('success', 'Article deleted.');
    }

    // Admin: List users
    public function users()
    {
        $users = User::where('role', 'user')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // Admin: Delete user
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Cannot delete admin.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted.');
    }

    // Admin: List admins
    public function admins()
    {
        $admins = User::where('role', 'admin')->paginate(10);
        return view('admin.admins.index', compact('admins'));
    }
}
