<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/** Pencarian produk dan berita (ikon search di header). */
class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        [$products, $news] = $this->run($q, 24);

        return view('search', [
            'q' => $q,
            'products' => $products,
            'news' => $news,
            'newsRoute' => Route::has('news.show'),
        ]);
    }

    public function suggest(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        [$products, $news] = $this->run($q, 4);

        $out = [];
        foreach ($products as $p) {
            $out[] = ['type' => __('site.product'), 'title' => $p->name, 'url' => route('product.show', $p->slug)];
        }
        foreach ($news as $n) {
            $out[] = ['type' => __('site.news'), 'title' => $n->tr('title'), 'url' => Route::has('news.show') ? route('news.show', $n->slug) : url('/').'#berita'];
        }
        return response()->json($out);
    }

    private function run(string $q, int $limit): array
    {
        if (mb_strlen($q) < 2) {
            return [collect(), collect()];
        }
        $like = '%'.str_replace(['%', '_'], ['\%', '\_'], $q).'%';

        $products = ProductModel::query()->where('is_active', true)
            ->where(fn ($w) => $w->where('name', 'like', $like)->orWhere('subtitle', 'like', $like)->orWhere('description', 'like', $like))
            ->orderBy('sort_order')->limit($limit)->get();

        $codes = array_unique([app()->getLocale(), config('site.default_locale')]);
        $news = News::query()->published()
            ->where(function ($w) use ($codes, $like) {
                foreach ($codes as $c) {
                    $w->orWhere("title->$c", 'like', $like);
                }
            })
            ->orderByDesc('published_at')->limit($limit)->get();

        return [$products, $news];
    }
}
