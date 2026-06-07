{{-- SEO Meta Tags Partial --}} 
{{-- Renders comprehensive SEO tags from $meta array passed by controllers --}}

@isset($meta)
    {{-- Basic Meta Tags --}}
    <meta name="description" content="{{ Str::limit($meta['description'] ?? '', 160) }}">
    <meta name="keywords" content="{{ $meta['keywords'] ?? '' }}">
    <meta name="robots" content="{{ $meta['robots'] ?? 'index, follow' }}">
    <meta name="author" content="{{ $setting_web->name ?? config('app.name') }}">

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ $meta['canonical'] ?? url()->current() }}">

    {{-- Open Graph Tags --}}
    <meta property="og:title" content="{{ Str::limit($meta['title'] ?? ($title ?? config('app.name')), 70) }}">
    <meta property="og:description" content="{{ Str::limit($meta['description'] ?? '', 160) }}">
    <meta property="og:type" content="{{ $meta['og_type'] ?? 'website' }}">
    <meta property="og:url" content="{{ $meta['canonical'] ?? url()->current() }}">
    @if (!empty($meta['og_image']))
        <meta property="og:image" content="{{ $meta['og_image'] }}">
        <meta property="og:image:alt" content="{{ Str::limit($meta['title'] ?? ($title ?? ''), 100) }}">
    @endif
    <meta property="og:site_name" content="{{ $setting_web->name ?? config('app.name') }}">
    <meta property="og:locale" content="id_ID">

    {{-- Twitter Card Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ Str::limit($meta['title'] ?? ($title ?? config('app.name')), 70) }}">
    <meta name="twitter:description" content="{{ Str::limit($meta['description'] ?? '', 160) }}">
    @if (!empty($meta['og_image']))
        <meta name="twitter:image" content="{{ $meta['og_image'] }}">
    @endif
@endisset
