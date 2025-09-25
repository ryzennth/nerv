<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * List all articles.
     */
    public function index()
    {
        $articles = Article::with('user')
            ->latest()
            ->paginate(10);

        return Inertia::render('Articles/Index', [
            'articles' => $articles,
        ]);
    }

    /**
     * Show form to create article.
     */
    public function create()
    {
        $categories = Category::all(['id', 'name']);
        $tags = Tag::all(['id', 'name']);

        return Inertia::render('Articles/Create', [
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }


    /**
     * Store new article.
     */
public function store(Request $request)
{
    $validated = $request->validate([
        'title'       => 'required|string|max:255',
        'content'     => 'required|string',
        'cover'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=700,min_height=450',
        'category_id' => 'required|exists:categories,id',
        'tags'        => 'array',
        'tags.*'      => 'exists:tags,id',
    ]);

    if ($request->hasFile('cover')) {
        $validated['cover'] = $request->file('cover')->store('articles', 'public');
    }

    $article = $request->user()->articles()->create([
        ...$validated,
        'status' => 'pending',
    ]);

    $article->tags()->sync($validated['tags'] ?? []);

    return redirect()
        ->route('articles.index')
        ->with('success', 'Article submitted for approval!');
}


    /**
     * Show a single article.
     */
    public function show(Request $request, Article $article)
    {
    // hit = setiap refresh
    $article->increment('hits');

    // view = per session
    $sessionKey = 'article_viewed_' . $article->id;
    if (!$request->session()->has($sessionKey)) {
    $article->increment('views');
    $request->session()->put($sessionKey, true);
    }
        
        return Inertia::render('Articles/Show', [
        'article' => $article->load('user'),
    ]);

    
    }

    /**
     * Show edit form.
     */
    public function edit(Article $article)
    {
        $this->authorize('update', $article);

        return Inertia::render('Articles/Edit', [
            'article' => $article,
        ]);
    }

    /**
     * Update article.
     */
    public function update(Request $request, Article $article)
    {
        $this->authorize('update', $article);

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'cover'   => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048', // 2MB
                'dimensions:min_width=700,min_height=450',
            ],
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);
        if ($request->hasFile('cover')) {
            // Hapus cover lama (opsional)
            if ($article->cover) {
                Storage::disk('public')->delete($article->cover);
            }
            $validated['cover'] = $request->file('cover')->store('articles', 'public');
        }





        $article->update($validated);
        $article->tags()->sync($validated['tags'] ?? []);

        return redirect()
            ->route('articles.index')
            ->with('success', 'Article updated!');
    }

    /**
     * Delete article.
     */
    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);

        $article->delete();

        return redirect()
            ->route('articles.index')
            ->with('success', 'Article deleted successfully.');
    }

    /**
     * Moderation page for admin/editor.
     */
    public function moderation()
    {
        $articles = Article::where('status', 'pending')->get();

        return Inertia::render('Articles/Moderation', [
            'articles' => $articles,
        ]);
    }

    /**
     * Approve an article.
     */
    public function approve(Article $article)
    {
        $this->authorize('approve', $article);

        $article->update(['status' => 'approved']);
        return back()->with('success', 'Article approved.');
    }

    public function reject(Article $article)
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

    // filter berdasarkan author (opsional)
    if ($request->filled('author')) {
        $query->where('user_id', $request->author);
    }

    // filter tanggal (dari - sampai)
    if ($request->filled('from_date')) {
        $query->whereDate('created_at', '>=', $request->from_date);
    }
    if ($request->filled('to_date')) {
        $query->whereDate('created_at', '<=', $request->to_date);
    }

    $articles = $query->paginate(10);

    // buat dropdown author filter
    $authors = \App\Models\User::has('articles')->get();

    return inertia('Articles/Approved', [
        'articles' => $articles,
        'authors'  => $authors,
        'filters'  => $request->only('author'),
    ]);
}

    /**
     * Tampilkan daftar artikel yang sudah dihapus (soft delete).
     */
    public function trashed()
    {
        $articles = Article::onlyTrashed()
            ->with('user')
            ->latest('deleted_at')
            ->paginate(10);

        return Inertia::render('Articles/Trashed', [
            'articles' => $articles,
        ]);
    }

    /**
     * Restore artikel yang dihapus (soft delete).
     */
    public function restore($id)
    {
        $article = Article::onlyTrashed()->findOrFail($id);

        $this->authorize('restore', $article);

        $article->restore();

        return back()->with('success', 'Article restored successfully.');
    }

    /**
     * Hapus permanen artikel.
     */
    public function forceDelete($id)
    {
        $article = Article::onlyTrashed()->findOrFail($id);

        $this->authorize('forceDelete', $article);

        // hapus cover dari storage kalau ada
        if ($article->cover) {
            Storage::disk('public')->delete($article->cover);
        }

        $article->forceDelete();

        return back()->with('success', 'Article permanently deleted.');
    }

    /**
     * Bulk delete (soft delete banyak artikel sekaligus).
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!empty($ids)) {
            Article::whereIn('id', $ids)->delete();
        }

        return back()->with('success', 'Selected articles deleted successfully.');
    }



}
