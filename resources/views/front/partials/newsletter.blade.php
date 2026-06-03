    <!-- NEWSLETTER-1
    ============================================= -->
    <div id="newsletter-1" class="bg-10 newsletter-section division">
        <div class="container white-color">

            <!-- SECTION TITLE -->
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="section-title text-center mb-40">
                        <h3 class="h3-md">Subscribe untuk Info Terbaru</h3>
                        <p class="p-xl">Dapatkan informasi terbaru dari kami</p>
                    </div>
                </div>
            </div>

            <!-- NEWSLETTER FORM -->
            <div class="row">
                <div class="col-md-10 col-lg-8 offset-md-1 offset-lg-2">
                    <div class="newsletter-txt text-center">
                        <form class="newsletter-form" id="newsletter-form">
                            @csrf
                            <div class="input-group">
                                <input type="email" name="email" class="form-control" placeholder="Masukkan alamat email Anda"
                                    required id="s-email">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-theme tra-white-hover" id="newsletter-btn">Subscribe</button>
                                </span>
                            </div>

                            <!-- Small Text -->
                            <p class="p-sm">Tidak ada spam, hanya informasi bermanfaat dari kami</p>

                            <!-- Newsletter Form Notification -->
                            <div id="newsletter-message" style="display: none; margin-top: 15px; padding: 10px 20px; border-radius: 6px; font-size: 14px;"></div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div> <!-- END NEWSLETTER-1 -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('newsletter-form');
            if (!form) return;

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                var btn = document.getElementById('newsletter-btn');
                var msgDiv = document.getElementById('newsletter-message');
                var emailInput = document.getElementById('s-email');
                var originalText = btn.textContent;

                btn.textContent = 'Mengirim...';
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

                    if (xhr.status === 200) {
                        msgDiv.style.background = 'rgba(40, 167, 69, 0.2)';
                        msgDiv.style.color = '#fff';
                        emailInput.value = '';
                    } else {
                        msgDiv.style.background = 'rgba(220, 53, 69, 0.2)';
                        msgDiv.style.color = '#fff';
                    }

                    btn.textContent = originalText;
                    btn.disabled = false;

                    setTimeout(function() {
                        msgDiv.style.display = 'none';
                    }, 5000);
                };

                xhr.onerror = function() {
                    msgDiv.style.display = 'block';
                    msgDiv.style.background = 'rgba(220, 53, 69, 0.2)';
                    msgDiv.style.color = '#fff';
                    msgDiv.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                    btn.textContent = originalText;
                    btn.disabled = false;
                };

                xhr.send(JSON.stringify({ email: emailInput.value }));
            });
        });
    </script>
