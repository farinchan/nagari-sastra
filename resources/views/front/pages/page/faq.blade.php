@extends('front.app')

@section('content')

    <style>
        .faq-card {
            border: 1px solid #e8e8e8;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 12px;
            transition: border-color 0.3s ease;
        }
        .faq-card:hover {
            border-color: #ccc;
        }
        .faq-header {
            background: #fff;
            border-bottom: none;
            padding: 0;
        }
        .faq-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 18px 25px;
            font-size: 16px;
            font-weight: 600;
            color: #333;
            text-decoration: none !important;
            border: none;
            background: none;
            text-align: left;
            cursor: pointer;
        }
        .faq-btn:hover {
            color: #333;
            text-decoration: none;
        }
        .faq-btn:focus {
            outline: none;
            box-shadow: none;
        }
        .faq-btn .faq-arrow {
            font-size: 10px;
            flex-shrink: 0;
            transition: transform 0.3s ease;
        }
        .faq-btn:not(.collapsed) .faq-arrow {
            transform: rotate(180deg);
        }
        .faq-body {
            padding: 0 25px 20px 25px;
        }
        .faq-body p {
            line-height: 1.7;
            color: #666;
            margin-bottom: 0;
        }
        .faq-search-box {
            max-width: 500px;
            margin: 0 auto 40px;
            position: relative;
        }
        .faq-search-box input {
            width: 100%;
            padding: 14px 20px 14px 48px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
            transition: border-color 0.3s;
        }
        .faq-search-box input:focus {
            outline: none;
            border-color: #999;
        }
        .faq-search-box .search-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            color: #999;
        }
        .faq-empty {
            border: 2px dashed #ddd;
            border-radius: 6px;
            max-width: 500px;
            margin: 0 auto;
        }
    </style>

    <!-- FAQ PAGE
    ============================================= -->
    <section id="faq-page" class="wide-60 division">
        <div class="container">

            <!-- SECTION TITLE -->
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="section-title text-center mb-40">
                        <h3 class="h3-md">Pertanyaan yang Sering Diajukan</h3>
                        <p class="p-xl grey-color">Temukan jawaban dari pertanyaan yang paling sering ditanyakan</p>
                    </div>
                </div>
            </div>

            @if($list_faq->isEmpty())
                <div class="row">
                    <div class="col-12 text-center py-4">
                        <div class="bg-white p-5 faq-empty">
                            <div class="ico-55 mb-20 grey-color"><span class="flaticon-help"></span></div>
                            <h5 class="h5-xs">Belum Ada FAQ</h5>
                            <p class="p-md grey-color mb-0">Pertanyaan yang sering diajukan akan ditampilkan di sini.</p>
                        </div>
                    </div>
                </div>
            @else
                <!-- SEARCH -->
                <div class="row">
                    <div class="col-lg-10 offset-lg-1">
                        <div class="faq-search-box">
                            <span class="flaticon-loupe search-icon"></span>
                            <input type="text" id="faqSearch" placeholder="Cari pertanyaan..." autocomplete="off">
                        </div>
                    </div>
                </div>

                <!-- FAQ ACCORDION -->
                <div class="row">
                    <div class="col-lg-10 offset-lg-1">
                        <div id="faqAccordion">
                            @foreach($list_faq as $faq)
                                <div class="card faq-card wow fadeInUp" data-wow-delay="{{ $loop->index * 0.06 }}s">
                                    <div class="card-header faq-header" id="faqHead{{ $faq->id }}">
                                        <button class="faq-btn collapsed"
                                                data-toggle="collapse" data-target="#faqBody{{ $faq->id }}"
                                                aria-expanded="false" aria-controls="faqBody{{ $faq->id }}">
                                            <span class="faq-question" style="padding-right: 15px;">{{ $faq->question }}</span>
                                            <span class="flaticon-down-arrow faq-arrow"></span>
                                        </button>
                                    </div>
                                    <div id="faqBody{{ $faq->id }}" class="collapse" aria-labelledby="faqHead{{ $faq->id }}" data-parent="#faqAccordion">
                                        <div class="faq-body">
                                            <p>{!! nl2br(e($faq->answer)) !!}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- NO RESULTS -->
                        <div id="faqNoResults" class="text-center py-4" style="display: none;">
                            <p class="p-md grey-color mb-0">Tidak ada pertanyaan yang cocok dengan pencarian Anda.</p>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </section>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#faqSearch').on('keyup', function() {
            var query = $(this).val().toLowerCase();
            var visibleCount = 0;

            $('#faqAccordion .faq-card').each(function() {
                var question = $(this).find('.faq-question').text().toLowerCase();
                var answer = $(this).find('.faq-body').text().toLowerCase();

                if (question.indexOf(query) > -1 || answer.indexOf(query) > -1) {
                    $(this).show();
                    visibleCount++;
                } else {
                    $(this).hide();
                }
            });

            if (visibleCount === 0 && query.length > 0) {
                $('#faqNoResults').show();
            } else {
                $('#faqNoResults').hide();
            }
        });
    });
</script>
@endsection
