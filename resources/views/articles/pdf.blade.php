<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $article->title }}</title>
    <style>
        /* Menggunakan font dasar untuk kompatibilitas maksimal */
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        h1 { font-size: 24px; color: #000; }
        .meta { color: #555; font-size: 12px; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;}
        img { max-width: 100%; height: auto; margin-bottom: 20px; }
        /* Styling dasar untuk konten dari QuillEditor */
        p { margin-bottom: 10px; }
        strong { font-weight: bold; }
        em { font-style: italic; }
        ul, ol { padding-left: 20px; }
    </style>
</head>
<body>
    <h1>{{ $article->title }}</h1>
    <div class="meta">
        By {{ $article->user->name }} | Published on: {{ $article->created_at->format('d F Y') }}
    </div>

    @if($article->cover)
        <img src="{{ storage_path('app/public/' . $article->cover) }}" alt="Cover Image">
    @endif

    <div>
        {!! $article->content !!}
    </div>
</body>
</html>