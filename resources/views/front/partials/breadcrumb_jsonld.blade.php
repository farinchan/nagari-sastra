{{-- Breadcrumb JSON-LD Structured Data --}}
@php
    $hasBreadcrumbs = isset($breadcrumbs) && is_array($breadcrumbs) && count($breadcrumbs) > 0;
    $breadcrumbJsonLd = null;
    if ($hasBreadcrumbs) {
        $breadcrumbJsonLd = [
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
        ];
    }
@endphp
@if($hasBreadcrumbs && $breadcrumbJsonLd)
<script type="application/ld+json">
{!! json_encode($breadcrumbJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endif
