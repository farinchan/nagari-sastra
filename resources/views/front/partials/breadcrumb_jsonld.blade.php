{{-- Breadcrumb JSON-LD Structured Data --}}
@php
    $hasBreadcrumbs = isset($breadcrumbs) && is_array($breadcrumbs) && count($breadcrumbs) > 0;
@endphp
@if($hasBreadcrumbs)
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => collect($breadcrumbs)->map(function ($breadcrumb, $index) {
        return [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $breadcrumb['name'] ?? '',
            'item' => $breadcrumb['link'] ?? '',
        ];
    })->values()->toArray(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endif
