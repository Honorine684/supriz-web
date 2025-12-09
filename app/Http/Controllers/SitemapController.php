<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $baseUrl = config('app.url');

        $urls = [
            [
                'loc' => $baseUrl,
                'lastmod' => now()->toDateString(),
                'changefreq' => 'daily',
                'priority' => '1.0'
            ],
            [
                'loc' => $baseUrl . '/nouveau-message',
                'lastmod' => now()->toDateString(),
                'changefreq' => 'weekly',
                'priority' => '0.9'
            ],
            [
                'loc' => $baseUrl . '/politique-confidentialite',
                'lastmod' => now()->toDateString(),
                'changefreq' => 'monthly',
                'priority' => '0.5'
            ],
            [
                'loc' => $baseUrl . '/conditions-utilisation',
                'lastmod' => now()->toDateString(),
                'changefreq' => 'monthly',
                'priority' => '0.5'
            ],
        ];

        $content = view('sitemap', compact('urls'));

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
}
