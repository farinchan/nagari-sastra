{{-- SEO Meta Tags Partial --}}
{{-- Renders comprehensive SEO tags from $meta array passed by controllers --}}

@isset($meta)
    @php
        // Ensure og_image is always a full absolute URL
        $ogImage = $meta['og_image'] ?? null;
        if ($ogImage && !Str::startsWith($ogImage, ['http://', 'https://'])) {
            $ogImage = url($ogImage);
        }
    @endphp

    {{-- Basic Meta Tags --}}
    <meta name="description" content="{{ Str::limit($meta['description'] ?? '', 160) }}">
    <meta name="keywords" content="{{ $meta['keywords'] ?? '' }}">
    <meta name="robots" content="{{ $meta['robots'] ?? 'index, follow' }}">
    <meta name="author" content="{{ $meta['author'] ?? $setting_web->name ?? config('app.name') }}">

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ $meta['canonical'] ?? url()->current() }}">

    {{-- Open Graph Tags --}}
    <meta property="og:title" content="{{ Str::limit($meta['title'] ?? ($title ?? config('app.name')), 70) }}">
    <meta property="og:description" content="{{ Str::limit($meta['description'] ?? '', 160) }}">
    <meta property="og:type" content="{{ $meta['og_type'] ?? 'website' }}">
    <meta property="og:url" content="{{ $meta['canonical'] ?? url()->current() }}">
    @if ($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
        <meta property="og:image:alt" content="{{ Str::limit($meta['title'] ?? ($title ?? ''), 100) }}">
    @endif
    <meta property="og:site_name" content="{{ $setting_web->name ?? config('app.name') }}">
    <meta property="og:locale" content="id_ID">

    {{-- Twitter Card Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ Str::limit($meta['title'] ?? ($title ?? config('app.name')), 70) }}">
    <meta name="twitter:description" content="{{ Str::limit($meta['description'] ?? '', 160) }}">
    @if ($ogImage)
        <meta name="twitter:image" content="{{ $ogImage }}">
    @endif
@endisset
