@php
    $journals = \App\Models\Journal::all();
    $setting_web = \App\Models\SettingWebsite::first();
@endphp

<footer id="footer-2" class="footer division">
    <div class="container">


        <!-- FOOTER CONTENT -->
        <div class="row">


            <!-- FOOTER INFO -->
            <div class="col-md-10 col-lg-5 col-xl-4">
                <div class="footer-info mb-40">

                    <!-- Footer Logo -->
                    <div class="footer-logo"><img src="{{ $setting_web?->getLogo() ?? '' }}" alt="footer-logo" /></div>

                    <!-- Text -->
                    <p>
                        {{ Str::limit($setting_web?->getAboutRaw() ?? '', 100, '...') }}
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
                            <p><a href="https://torkatatech.com/">Torkata Tech Solution</a></p>
                        </li>
                        <li>
                            <p><a href="https://torkaumrah.com/">Torkata Umrah & Travel</a></p>
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
                                <p><a href="{{ route('journal.detail', $journal->url_path) }}">{{ $journal->title }}</a></p>
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
                    <ul class="bottom-footer-list ico-15 text-right clearfix">
                        @if ($setting_web?->facebook)
                            <li>
                                <p class="first-list-link"><a href="#"><span class="flaticon-facebook"></span>
                                        Facebook</a></p>
                            </li>
                        @endif

                        @if ($setting_web?->instagram)
                            <li>
                                <p><a href="{{ $setting_web->instagram }}"><span class="flaticon-instagram"></span> Instagram</a></p>
                            </li>
                        @endif
                        @if ($setting_web?->linkedin)
                            <li>
                                <p><a href="{{ $setting_web->linkedin }}"><span class="flaticon-linkedin"></span> LinkedIn</a></p>
                            </li>
                        @endif
                        @if ($setting_web?->whatsapp)
                            <li>
                                <p class="last-li"><a href="https://wa.me/{{ $setting_web->whatsapp }}"><span class="flaticon-whatsapp"></span> WhatsApp</a></p>
                            </li>
                        @endif
                    </ul>
                </div>


            </div> <!-- End row -->
        </div> <!-- END BOTTOM FOOTER -->


    </div> <!-- End container -->
</footer>
