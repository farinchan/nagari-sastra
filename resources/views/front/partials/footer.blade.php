@php
    $journals = \App\Models\Journal::all();
    $setting_web = \App\Models\SettingWebsite::first();
@endphp

<style>
    .custom-footer {
        background: #f8fafc !important;
        color: #475569 !important;
        padding-top: 75px !important;
        padding-bottom: 25px !important;
        font-family: 'Inter', sans-serif !important;
    }
    .custom-footer .footer-info p {
        color: #475569 !important;
        font-size: 0.9rem !important;
        line-height: 1.6 !important;
        margin-top: 15px !important;
    }
    .custom-footer h6.h6-xl {
        color: #0f172a !important;
        font-size: 1.05rem !important;
        font-weight: 700 !important;
        letter-spacing: 0.5px !important;
        text-transform: uppercase !important;
        margin-bottom: 20px !important;
        margin-top: 8px !important;
        position: relative !important;
        padding-bottom: 10px !important;
    }
    .custom-footer h6.h6-xl::after {
        content: "" !important;
        position: absolute !important;
        left: 0 !important;
        bottom: 0 !important;
        width: 35px !important;
        height: 3px !important;
        background: #007bff !important;
        border-radius: 2px !important;
    }
    .custom-footer .footer-links ul {
        list-style: none !important;
        padding-left: 0 !important;
        margin: 0 !important;
    }
    .custom-footer .footer-links li {
        margin-bottom: 10px !important;
        padding: 0 !important;
    }
    .custom-footer .footer-links li p {
        margin: 0 !important;
    }
    .custom-footer .footer-links a {
        color: #334155 !important;
        font-size: 0.9rem !important;
        text-decoration: none !important;
        transition: all 0.25s ease !important;
        display: inline-flex !important;
        align-items: center !important;
    }
    .custom-footer .footer-links a::before {
        content: "›" !important;
        font-weight: bold !important;
        font-size: 1.1rem !important;
        margin-right: 8px !important;
        color: #007bff !important;
        transition: transform 0.25s ease !important;
    }
    .custom-footer .footer-links a:hover {
        color: #0284c7 !important;
        padding-left: 5px !important;
    }
    .custom-footer .footer-links a:hover::before {
        color: #0284c7 !important;
    }
    .custom-footer .footer-form p {
        color: #475569 !important;
        font-size: 0.9rem !important;
        line-height: 1.5 !important;
    }
    .custom-footer .newsletter-form .input-group {
        background: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 10px !important;
        overflow: hidden !important;
        padding: 4px !important;
        transition: all 0.25s ease !important;
        display: flex !important;
        width: 100% !important;
    }
    .custom-footer .newsletter-form .input-group:focus-within {
        border-color: #007bff !important;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15) !important;
    }
    .custom-footer .newsletter-form input[type="email"] {
        background: transparent !important;
        border: none !important;
        color: #0f172a !important;
        box-shadow: none !important;
        font-size: 0.875rem !important;
        padding: 8px 12px !important;
        height: auto !important;
        flex-grow: 1 !important;
    }
    .custom-footer .newsletter-form input[type="email"]::placeholder {
        color: #94a3b8 !important;
    }
    .custom-footer .newsletter-form button.btn {
        background: #0284c7 !important;
        color: #fff !important;
        border-radius: 6px !important;
        padding: 8px 16px !important;
        transition: all 0.2s ease !important;
        border: none !important;
        height: auto !important;
    }
    .custom-footer .newsletter-form button.btn:hover {
        background: #0369a1 !important;
        transform: scale(1.02) !important;
    }
    .custom-footer .bottom-footer {
        border-top: 1px solid #e2e8f0 !important;
        padding-top: 30px !important;
        margin-top: 50px !important;
    }
    .custom-footer .footer-copyright p {
        color: #64748b !important;
        font-size: 0.85rem !important;
        margin: 0 !important;
    }
    .custom-footer .bottom-footer-list {
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
        display: flex !important;
        gap: 10px !important;
        justify-content: flex-end !important;
    }
    .custom-footer .bottom-footer-list li {
        display: inline-block !important;
    }
    .custom-footer .bottom-footer-list a {
        width: 36px !important;
        height: 36px !important;
        border-radius: 50% !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        color: #64748b !important;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
    }
    .custom-footer .bottom-footer-list a span {
        font-size: 1rem !important;
        color: inherit !important;
        line-height: 1 !important;
    }
    .custom-footer .bottom-footer-list a:hover {
        transform: translateY(-4px) scale(1.1) !important;
        background: #0284c7 !important;
        border-color: #0284c7 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3) !important;
    }
    /* Responsiveness */
    @media (max-width: 991px) {
        .custom-footer .bottom-footer-list {
            justify-content: center !important;
            margin-top: 15px !important;
            gap: 12px !important;
        }
        .custom-footer .footer-copyright {
            text-align: center !important;
        }
    }
</style>

<footer id="footer-2" class="footer division custom-footer">
    <div class="container">


        <!-- FOOTER CONTENT -->
        <div class="row">


            <!-- FOOTER INFO -->
            <div class="col-md-10 col-lg-5 col-xl-4">
                <div class="footer-info mb-40">

                    <!-- Footer Logo -->
                    <div class="footer-logo">
                        <img src="{{ $setting_web?->getLogo() ?? '' }}" alt="footer-logo" style="max-height: 50px;" />
                    </div>

                    <!-- Text -->
                    <p>
                        {{ Str::limit($setting_web?->getAboutRaw() ?? '', 120, '...') }}
                    </p>

                </div>
            </div>


            <!-- FOOTER PRODUCTS LINKS -->
            <div class="col-md-3 col-lg-2 col-xl-2 offset-xl-1">
                <div class="footer-links mb-40">

                    <!-- Title -->
                    <h6 class="h6-xl">Links</h6>

                    <!-- Footer List -->
                    <ul class="clearfix">
                        <li>
                            <p><a href="{{ route('home') }}">Home</a></p>
                        </li>
                        <li>
                            <p><a href="{{ route('journal.index') }}">Jurnal</a></p>
                        </li>
                        <li>
                            <p><a href="{{ route('news.index') }}">Berita</a></p>
                        </li>
                        <li>
                            <p><a href="{{ route('contact.index') }}">Kontak</a></p>
                        </li>
                        <li>
                            <p><a href="https://torkatatech.com/">Torkata Tech</a></p>
                        </li>
                        <li>
                            <p><a href="https://torkaumrah.com/">Torkata Umrah</a></p>
                        </li>
                    </ul>

                </div>
            </div>


            <!-- FOOTER COMPANY LINKS -->
            <div class="col-md-3 col-lg-2 col-xl-2">
                <div class="footer-links mb-40">

                    <!-- Title -->
                    <h6 class="h6-xl">Jurnal</h6>

                    <!-- Footer Links -->
                    <ul class="clearfix">
                        @foreach ($journals as $journal)
                            <li>
                                <p><a href="{{ route('journal.detail', $journal->url_path) }}">{{ Str::limit($journal->title, 20) }}</a></p>
                            </li>
                        @endforeach

                    </ul>

                </div>
            </div>


            <!-- FOOTER NEWSLETTER FORM -->
            <div class="col-md-6 col-lg-3 col-xl-3">
                <div class="footer-form mb-20">

                    <!-- Title -->
                    <h6 class="h6-xl">Ikuti Kami</h6>

                    <!-- Text -->
                    <p class="mb-20">
                        Dapatkan penawaran terbaik dari kami dengan masukkan email kamu disini
                    </p>

                    <!-- Newsletter Form Input -->
                    <form class="newsletter-form">

                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Email Address" required
                                id="s-email">
                            <span class="input-group-btn">
                                <button type="submit" class="btn ico-25">
                                    <span class="flaticon-arrow-right"></span>
                                </button>
                            </span>
                        </div>

                        <!-- Newsletter Form Notification -->
                        <label for="s-email" class="form-notification"></label>

                    </form>

                </div>
            </div> <!-- END FOOTER NEWSLETTER FORM -->


        </div> <!-- END FOOTER CONTENT -->


        <!-- BOTTOM FOOTER -->
        <div class="bottom-footer">
            <div class="row d-flex align-items-center">


                <!-- FOOTER COPYRIGHT -->
                <div class="col-lg-6">
                    <div class="footer-copyright">
                        <p>&copy; {{ date('Y') }} PT Nagari Sastra Group. All Rights Reserved</p>
                    </div>
                </div>


                <!-- BOTTOM FOOTER LINKS -->
                <div class="col-lg-6">
                    <ul class="bottom-footer-list">
                        @if ($setting_web?->facebook)
                            <li>
                                <a href="#" title="Facebook"><span class="flaticon-facebook"></span></a>
                            </li>
                        @endif

                        @if ($setting_web?->instagram)
                            <li>
                                <a href="{{ $setting_web->instagram }}" title="Instagram"><span class="flaticon-instagram"></span></a>
                            </li>
                        @endif
                        @if ($setting_web?->linkedin)
                            <li>
                                <a href="{{ $setting_web->linkedin }}" title="LinkedIn"><span class="flaticon-linkedin"></span></a>
                            </li>
                        @endif
                        @if ($setting_web?->whatsapp)
                            <li>
                                <a href="https://wa.me/{{ $setting_web->whatsapp }}" title="WhatsApp"><span class="flaticon-whatsapp"></span></a>
                            </li>
                        @endif
                    </ul>
                </div>


            </div> <!-- End row -->
        </div> <!-- END BOTTOM FOOTER -->


    </div> <!-- End container -->
</footer>
