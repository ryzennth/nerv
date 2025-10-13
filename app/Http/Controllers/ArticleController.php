<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ArticleController extends Controller
{
    /**
     * Terapkan policy ke resource method standar.
     */


    /**
     * Menampilkan semua artikel.
     */
    public function index(): Response
    {
        $articles = Article::with(['user:id,name', 'category:id,name'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Articles/Index', [
            'articles' => $articles,
        ]);
    }

    /**
     * Menampilkan form untuk membuat artikel baru.
     */
    public function create(): Response
    {
        return Inertia::render('Articles/Create', [
            'categories' => Category::all(['id', 'name']),
            'tags' => Tag::all(['id', 'name']),
        ]);
    }

    /**
     * Menyimpan artikel baru.
     */
    public function store(StoreArticleRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('articles', 'public');
        }

        /** @var \App\Models\Article $article */
        $article = Auth::user()->articles()->create(array_merge(
            $validated,
            ['status' => 'pending']
        ));

        if (!empty($validated['tags'])) {
            $article->tags()->sync($validated['tags']);
        }

        return to_route('articles.index')->with('success', 'Article submitted for approval!');
    }

    /**
     * Menampilkan satu artikel.
     */
    public function show(Request $request, Article $article): Response
    {
        if ($article->status !== 'approved' && !Auth::user()?->can('view', $article)) {
            abort(404);
        }

        $article->increment('hits');

        $sessionKey = 'article_viewed_' . $article->id;
        if (!$request->session()->has($sessionKey)) {
            $article->increment('views');
            $request->session()->put($sessionKey, true);
        }

        $popularArticles = Article::where('status', 'approved')
            ->where('id', '!=', $article->id) // Jangan tampilkan artikel yang sedang dibaca
            ->orderByDesc('hits')
            ->take(5)
            ->get(['id', 'title', 'slug', 'cover']); // Ambil hanya data yg perlu

        return Inertia::render('Articles/Show', [
            'article' => $article->load(['user:id,name,avatar,username', 'category:id,name', 'tags:id,name']),
            'popularArticles' => Article::where('status', 'approved')->orderByDesc('hits')->take(5)->get(['id', 'title', 'slug', 'cover', 'user_id']),
        ]);
    }

    /**
     * Menampilkan form untuk mengedit artikel.
     */
    public function edit(Article $article): Response
    {
        return Inertia::render('Articles/Edit', [
            'article' => $article->load(['category', 'tags']),
            'categories' => Category::all(['id', 'name']),
            'tags' => Tag::all(['id', 'name']),
        ]);
    }

    /**
     * Mengupdate artikel.
     */
    public function update(UpdateArticleRequest $request, Article $article): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('cover')) {
            if ($article->cover) {
                Storage::disk('public')->delete($article->cover);
            }
            $validated['cover'] = $request->file('cover')->store('articles', 'public');
        }

        $article->update($validated);
        
        $article->tags()->sync($validated['tags'] ?? []);

        return to_route('articles.index')->with('success', 'Article updated!');
    }

    /**
     * Menghapus artikel (soft delete).
     */
    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();
        return to_route('articles.index')->with('success', 'Article deleted successfully.');
    }

    public function moderation(): Response
    {
        $this->authorize('viewAny', Article::class); // Contoh otorisasi manual
        $articles = Article::where('status', 'pending')->with('user:id,name')->latest()->get();
        return Inertia::render('Articles/Moderation', compact('articles'));
    }

    public function approve(Article $article): RedirectResponse
    {
        $this->authorize('approve', $article);
        $article->update(['status' => 'approved']);
        return back()->with('success', 'Article approved.');
    }

    public function reject(Article $article): RedirectResponse
    {
        $this->authorize('reject', $article);
        $article->update(['status' => 'rejected']);
        return back()->with('success', 'Article rejected.');
    }

    public function approved(Request $request)
{

    $query = Article::with('user')
        ->where('status', 'approved')
        ->latest();

    if ($request->filled('author')) {
        $query->where('user_id', $request->author);
    }
    if ($request->filled('from_date')) {
        $query->whereDate('created_at', '>=', $request->from_date);
    }
    if ($request->filled('to_date')) {
        $query->whereDate('created_at', '<=', $request->to_date);
    }

    $articles = $query->paginate(10);
    $authors = \App\Models\User::has('articles')->get();

    return inertia('Articles/Approved', [
        'articles' => $articles,
        'authors'  => $authors,
        'filters'  => $request->only(['author', 'from_date', 'to_date']), // <-- PERBAIKAN DI SINI
    ]);
}

    public function trashed(): Response
    {
        $this->authorize('viewTrash', Article::class);
        $articles = Article::onlyTrashed()->with('user:id,name')->latest('deleted_at')->paginate(10);
        return Inertia::render('Articles/Trashed', compact('articles'));
    }

    public function restore(Article $article): RedirectResponse
    {
        $this->authorize('restore', $article);
        $article->restore();
        return back()->with('success', 'Article restored successfully.');
    }

    public function forceDelete(Article $article): RedirectResponse
    {
        $this->authorize('forceDelete', $article);
        if ($article->cover) {
            Storage::disk('public')->delete($article->cover);
        }
        $article->forceDelete();
        return back()->with('success', 'Article permanently deleted.');
    }

    public function bulkDelete(Request $request): RedirectResponse
    {
        $ids = $request->validate(['ids' => 'required|array'])['ids'];
        $articles = Article::whereIn('id', $ids)->get();
        foreach ($articles as $article) {
            $this->authorize('delete', $article);
            $article->delete();
        }
        return back()->with('success', 'Selected articles deleted successfully.');
    }
}