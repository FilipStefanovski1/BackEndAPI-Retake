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

    // Dashboard homepage
    public function dashboard()
    {
        $articleCount = Article::count();
        $userCount = User::where('role', 'user')->count();
        $adminCount = User::where('role', 'admin')->count();
        $articles = Article::latest()->get();

        return view('admin.dashboard', compact('articleCount', 'userCount', 'adminCount', 'articles'));
    }

    // Show all articles (paginated)
    public function articles()
    {
        $articles = Article::latest()->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    // Show create article form
    public function createArticle()
    {
        return view('admin.articles.create');
    }

    // Store new article
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

    // Show edit form
    public function editArticle($id)
    {
        $article = Article::findOrFail($id);
        return view('admin.articles.edit', compact('article'));
    }

    // Update article
    public function updateArticle(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image',
            'published' => 'nullable|boolean'
        ]);

        // Handle optional image replacement
        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $article->image = $request->file('image')->store('articles', 'public');
        }

        // Update remaining fields
        $article->title = $request->title;
        $article->content = $request->content;
        $article->published = $request->has('published');

        $article->save(); // ✅ Important to actually save the model

        return redirect()->route('admin.articles')->with('success', 'Article updated.');
    }

    // Delete article
    public function deleteArticle($id)
    {
        $article = Article::findOrFail($id);

        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        return redirect()->route('admin.articles')->with('success', 'Article deleted.');
    }

    // List all regular users
    public function users()
    {
        $users = User::where('role', 'user')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // Delete user
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Cannot delete admin.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted.');
    }

    // List all admins
    public function admins()
    {
        $admins = User::where('role', 'admin')->paginate(10);
        return view('admin.admins.index', compact('admins'));
    }
}
