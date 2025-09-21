<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('user')->latest()->paginate(10);
        return Inertia::render('Articles/Index', [
            'articles' => $articles
        ]);
    }

    public function create()
    {
        return Inertia::render('Articles/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $request->user()->articles()->create([
            'title'   => $request->title,
            'content' => $request->content,
            'status' => 'pending',
        ]);


        return redirect()->route('articles.index')->with('success', 'Article submitted for approval!');
    }

    public function show(Article $article)
    {
        return Inertia::render('Articles/Show', [
            'article' => $article->load('user')
        ]);
    }

    public function edit(Article $article)
    {
        $this->authorize('update', $article);

        return Inertia::render('Articles/Edit', [
            'article' => $article
        ]);
    }

    public function update(Request $request, Article $article)
    {
        $this->authorize('update', $article);

        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $article->update($request->only('title', 'content'));

        return redirect()->route('articles.index')->with('success', 'Article updated!');
    }

    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);

        $article->delete();

        return redirect()->route('articles.index')->with('success', 'Article deleted!');
    }

    // 👑 Admin: approval
public function moderation()
{
    $articles = Article::where('status', 'pending')->get();

    return inertia('Articles/Moderation', [
        'articles' => $articles,
    ]);
}

public function approve(Article $article)
{
    $article->update(['status' => 'approved']);
    return back()->with('success', 'Article approved.');
}

public function reject(Article $article)
{
    $article->update(['status' => 'rejected']);
    return back()->with('success', 'Article rejected.');
}



}
