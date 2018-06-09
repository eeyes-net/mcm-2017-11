<?php

namespace App\Libraries;

use App\Match;
use App\Post;
use App\Recruit;
use Illuminate\Support\Facades\App;

class GenerateSitemap
{
    public static function getPostImages($html)
    {
        $result = [];
        if (preg_match_all('/<img.*?>/su', $html, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                if (preg_match('/src="(.*?)"/u', $match[0], $matches1)) {
                    $result[] = [
                        'url' => url($matches1[1]),
                    ];
                }
            }
        }
        return $result;
    }

    public static function sitemap()
    {
        /** @var \Roumen\Sitemap\Sitemap $sitemap */
        $sitemap = App::make("sitemap");
        $sitemap->setCache(null, 1440);
        if ($sitemap->isCached()) {
            return $sitemap;
        }

        $page = null;
        do {
            $paginator = Post::latest()->paginate(12, ['id', 'title', 'content', 'created_at', 'updated_at'], 'page', $page);
            $page = $paginator->currentPage();

            $item = $paginator[0];
            if ($page === 1) {
                $sitemap->add(url('/'), $item->updated_at ? $item->updated_at->toAtomString() : $item->created_at->toAtomString(), '1.0', 'daily');
            } else {
                $sitemap->add(url('/?page=' . $page), $item->updated_at ? $item->updated_at->toAtomString() : $item->created_at->toAtomString(), '1.0', 'daily');
            }

            foreach ($paginator as $item) {
                $sitemap->add(url('/post/' . $item->id), $item->updated_at ? $item->updated_at->toAtomString() : $item->created_at->toAtomString(), null, 'weekly', self::getPostImages($item->content), $item->title);
            }

            ++$page;
        } while ($paginator->hasMorePages());

        $page = null;
        do {
            $paginator = Match::latest()->paginate(12, ['id', 'created_at', 'updated_at'], 'page', $page);
            $page = $paginator->currentPage();

            $item = $paginator[0];
            if ($page === 1) {
                $sitemap->add(url('/match'), $item->updated_at ? $item->updated_at->toAtomString() : $item->created_at->toAtomString(), '0.8', 'daily');
            } else {
                $sitemap->add(url('/match?page=' . $page), $item->updated_at ? $item->updated_at->toAtomString() : $item->created_at->toAtomString(), '0.8', 'daily');
            }

            ++$page;
        } while ($paginator->hasMorePages());

        $page = null;
        do {
            $paginator = Recruit::latest()->paginate(12, ['id', 'created_at', 'updated_at'], 'page', $page);
            $page = $paginator->currentPage();

            $item = $paginator[0];
            if ($page === 1) {
                $sitemap->add(url('/recruit'), $item->updated_at ? $item->updated_at->toAtomString() : $item->created_at->toAtomString(), '0.8', 'daily');
            } else {
                $sitemap->add(url('/recruit?page=' . $page), $item->updated_at ? $item->updated_at->toAtomString() : $item->created_at->toAtomString(), '0.8', 'daily');
            }

            ++$page;
        } while ($paginator->hasMorePages());

        return $sitemap;
    }
}
