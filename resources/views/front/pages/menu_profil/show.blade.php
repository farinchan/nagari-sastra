@extends('front.app')

@section('seo')
@php
    $profilSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'AboutPage',
        'name' => $menu_profil->name,
        'description' => Str::limit(strip_tags($menu_profil->content), 160),
        'url' => route('profil.show', $menu_profil->slug),
        'publisher' => [
            '@type' => 'Organization',
            'name' => $setting_web->name ?? config('app.name'),
        ],
    ];
    if (!empty($menu_profil->image)) {
        $profilSchema['image'] = Str::startsWith($menu_profil->image, ['http://', 'https://']) ? $menu_profil->image : url($menu_profil->image);
    }
@endphp
<script type="application/ld+json">
{!! json_encode($profilSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('content')

    <!-- PROFIL DETAIL SECTION
    ============================================= -->
    <section id="profil-detail" class="wide-60 blog-page-section division">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="posts-wrapper pr-15 pl-15">

                        <!-- TITLE -->
                        <h1 class="h3-md mb-25">{{ $menu_profil->name }}</h1>

                        <!-- FEATURED IMAGE (IF ANY) -->
                        @if(!empty($menu_profil->image))
                            <div class="mb-35 radius-06 overflow-hidden">
                                <img src="{{ Str::startsWith($menu_profil->image, ['http://', 'https://']) ? $menu_profil->image : asset($menu_profil->image) }}"
                                     alt="{{ $menu_profil->name }}"
                                     class="img-fluid w-100" style="max-height: 420px; object-fit: cover;">
                            </div>
                        @endif

                        <!-- MAIN CONTENT BODY -->
                        <div class="single-post-txt prose" style="font-size: 16px; line-height: 1.8; color: #444;">
                            {!! $menu_profil->content !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
