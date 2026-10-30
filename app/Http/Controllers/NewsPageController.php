<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

/** Halaman berita untuk pengunjung website (bukan admin). */
class NewsPageController extends Controller
{
    /** Halaman "Lihat Lainnya": hero + berita terkini + terpopuler + semua berita. */
    public function index()
    {
        $base = fn () => News::published()->orderByDesc('published_at')->orderByDesc('id');

        $latest = $base()->take(5)->get();

        $popular = News::published()->where('views', '>', 0)
            ->orderByDesc('views')->orderByDesc('published_at')
            ->take(3)->get();

        $all = $base()->paginate(9)->withQueryString()->fragment('semua');

        // data untuk hero yang bisa diganti saat diklik (dibaca JavaScript)
        $heroItems = $latest->concat($popular)->unique('id')
            ->mapWithKeys(fn (News $n) => [$n->id => $n->toCardArray()]);

        return view('news.index', compact('latest', 'popular', 'all', 'heroItems'));
    }

    /** Halaman baca berita. */
    public function show(Request $request, string $slug)
    {
        $news = News::published()->where('slug', $slug)->firstOrFail();

        // hitung pembaca 1x per sesi
        $key = 'news_viewed.' . $news->id;
        if (! $request->session()->has($key)) {
            $news->increment('views');
            $request->session()->put($key, true);
        }

        $others = News::published()->where('id', '!=', $news->id)
            ->orderByDesc('published_at')->orderByDesc('id')
            ->take(3)->get();

        return view('news.show', compact('news', 'others'));
    }
}
