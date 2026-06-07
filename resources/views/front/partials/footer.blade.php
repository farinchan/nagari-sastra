@php
    $journals = \App\Models\Journal::all();
    $setting_web = \App\Models\SettingWebsite::first();
@endphp

<footer id="footer-2" class="footer division" style="background-color: #f8f9fa;">
    <div class="container">

        <!-- FOOTER CONTENT -->
        <div class="row">

            <!-- FOOTER INFO -->
            <div class="col-md-10 col-lg-5 col-xl-4">
                <div class="footer-info mb-40">

                    <!-- Footer Logo -->
                    <div class="footer-logo">
                        <img src="{{ $setting_web?->getLogo() ?? '' }}" alt="{{ $setting_web->name ?? 'Logo' }}" style="max-height: 50px;" />
                    </div>

                    <!-- Text -->
                    <p class="p-md mt-15">
                        {{ Str::limit($setting_web?->getAboutRaw() ?? '', 300, '...') }}
                    </p>

                </div>
            </div>

            <!-- FOOTER LINKS -->
            <div class="col-md-3 col-lg-2 col-xl-2 offset-xl-1">
                <div class="footer-links mb-40">

                    <h6 class="h6-xl">Links</h6>

                    <ul class="footer-links-list clearfix">
                        <li>
                            <p><a href="{{ route('home') }}">Home</a></p>
                        </li>
                        <li>
                            <p><a href="{{ route('journal.index') }}">Jurnal</a></p>
                        </li>
                        <li>
                            <p><a href="{{ route('book.index') }}">Buku</a></p>
                        </li>
                        <li>
                            <p><a href="{{ route('news.index') }}">Berita</a></p>
                        </li>
                        <li>
                            <p><a href="{{ route('contact.index') }}">Kontak</a></p>
                        </li>
                        <li>
                            <p><a href="{{ route('page.terms') }}">Syarat & Ketentuan</a></p>
                        </li>
                        <li>
                            <p><a href="{{ route('page.privacy') }}">Kebijakan Privasi</a></p>
                        </li>
                    </ul>

                </div>
            </div>

            <!-- FOOTER JOURNAL LINKS -->
            <div class="col-md-3 col-lg-2 col-xl-2">
                <div class="footer-links mb-40">

                    <h6 class="h6-xl">Jurnal</h6>

                    <ul class="footer-links-list clearfix">
                        @foreach ($journals as $journal)
                            <li>
                                <p><a href="{{ route('journal.detail', $journal->url_path) }}">{{ Str::limit($journal->title, 25) }}</a></p>
                            </li>
                        @endforeach
                    </ul>

                </div>
            </div>

            <!-- FOOTER NEWSLETTER FORM -->
            <div class="col-md-6 col-lg-3 col-xl-3">
                <div class="footer-form mb-20">

                    <h6 class="h6-xl">Ikuti Kami</h6>

                    <p class="p-md">
                        Dapatkan informasi terbaru dari kami dengan memasukkan email Anda
                    </p>

                    <!-- Newsletter Form Input -->
                    <form class="newsletter-form" id="footer-newsletter-form">
                        @csrf
                        <div class="input-group">
                            <input type="email" name="email" class="form-control" placeholder="Email Address" required
                                id="footer-email">
                            <span class="input-group-btn">
                                <button type="submit" class="btn ico-25" id="footer-newsletter-btn">
                                    <span class="flaticon-arrow-right"></span>
                                </button>
                            </span>
                        </div>

                        <!-- Newsletter Form Notification -->
                        <div id="footer-newsletter-msg" class="form-notification" style="display: none; margin-top: 8px; font-size: 13px;"></div>

                    </form>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var form = document.getElementById('footer-newsletter-form');
                            if (!form) return;
                            form.addEventListener('submit', function(e) {
                                e.preventDefault();
                                var btn = document.getElementById('footer-newsletter-btn');
                                var msgDiv = document.getElementById('footer-newsletter-msg');
                                var emailInput = document.getElementById('footer-email');
                                btn.disabled = true;
                                msgDiv.style.display = 'none';
                                var xhr = new XMLHttpRequest();
                                xhr.open('POST', '{{ route("newsletter.subscribe") }}');
                                xhr.setRequestHeader('Content-Type', 'application/json');
                                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                                xhr.onload = function() {
                                    var data = JSON.parse(xhr.responseText);
                                    msgDiv.style.display = 'block';
                                    msgDiv.textContent = data.message;
                                    msgDiv.style.color = xhr.status === 200 ? '#28a745' : '#dc3545';
                                    if (xhr.status === 200) emailInput.value = '';
                                    btn.disabled = false;
                                    setTimeout(function() { msgDiv.style.display = 'none'; }, 5000);
                                };
                                xhr.onerror = function() {
                                    msgDiv.style.display = 'block';
                                    msgDiv.style.color = '#dc3545';
                                    msgDiv.textContent = 'Terjadi kesalahan.';
                                    btn.disabled = false;
                                };
                                xhr.send(JSON.stringify({ email: emailInput.value }));
                            });
                        });
                    </script>

                </div>
            </div>

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
                <div class="col-lg-6 text-right">
                    <ul class="bottom-footer-list">
                        @if ($setting_web?->facebook)
                            <li>
                                <a href="{{ $setting_web->facebook }}" title="Facebook" target="_blank" rel="noopener noreferrer" aria-label="Kunjungi halaman Facebook kami"><span class="flaticon-facebook"></span></a>
                            </li>
                        @endif
                        @if ($setting_web?->instagram)
                            <li>
                                <a href="{{ $setting_web->instagram }}" title="Instagram" target="_blank" rel="noopener noreferrer" aria-label="Kunjungi halaman Instagram kami"><span class="flaticon-instagram"></span></a>
                            </li>
                        @endif
                        @if ($setting_web?->linkedin)
                            <li>
                                <a href="{{ $setting_web->linkedin }}" title="LinkedIn" target="_blank" rel="noopener noreferrer" aria-label="Kunjungi halaman LinkedIn kami"><span class="flaticon-linkedin"></span></a>
                            </li>
                        @endif
                        @if ($setting_web?->whatsapp)
                            <li>
                                <a href="https://wa.me/{{ $setting_web->whatsapp }}" title="WhatsApp" target="_blank" rel="noopener noreferrer" aria-label="Hubungi kami via WhatsApp"><span class="flaticon-whatsapp"></span></a>
                            </li>
                        @endif
                    </ul>
                </div>

            </div>
        </div> <!-- END BOTTOM FOOTER -->

    </div>
</footer>
