{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- Homepage --}}
    <url>
        <loc>{{ route('home') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- Static Pages --}}
    <url>
        <loc>{{ route('news.index') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('journal.index') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('book.index') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('event.index') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('announcement.index') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>{{ route('contact.index') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{ route('page.faq') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>{{ route('page.terms') }}</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>
    <url>
        <loc>{{ route('page.privacy') }}</loc>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    {{-- News Articles --}}
    @foreach($news as $item)
    <url>
        <loc>{{ route('news.detail', $item->slug) }}</loc>
        <lastmod>{{ $item->updated_at->toW3cString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- Journals --}}
    @foreach($journals as $journal)
    <url>
        <loc>{{ route('journal.detail', $journal->url_path) }}</loc>
        <lastmod>{{ $journal->updated_at->toW3cString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    {{-- Books --}}
    @foreach($books as $book)
    <url>
        <loc>{{ route('book.show', $book->slug) }}</loc>
        <lastmod>{{ $book->updated_at->toW3cString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- Events --}}
    @foreach($events as $event)
    <url>
        <loc>{{ route('event.show', $event->slug) }}</loc>
        <lastmod>{{ $event->updated_at->toW3cString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- Announcements --}}
    @foreach($announcements as $announcement)
    <url>
        <loc>{{ route('announcement.show', $announcement->slug) }}</loc>
        <lastmod>{{ $announcement->updated_at->toW3cString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach

    {{-- Profile Menu Pages --}}
    @foreach($menuProfils as $menu)
    <url>
        <loc>{{ route('profil.show', $menu->slug) }}</loc>
        <lastmod>{{ $menu->updated_at->toW3cString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    @endforeach
</urlset>
