@extends('front.app')

@section('content')
    <!-- GOOGLE MAP
       ============================================= -->
    <div id="gmap" class="contacts-map division" style="margin-top: 0px;">
        <div class="google-map">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d725.0550393630955!2d100.39090787457243!3d-0.8550454986496887!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sid!2sid!4v1780815376566!5m2!1sid!2sid"
                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div> <!-- END GOOGLE MAP -->




    <!-- CONTACTS-3
       ============================================= -->
    <section id="contacts-3" class="bg-lightgrey wide-60 contacts-section division">
        <div class="container">


            <!-- SECTION TITLE -->
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="section-title text-center mb-60">

                        <!-- Title 	-->
                        <h2 class="h2-xs">Butuh bantuan?</h2>

                        <!-- Text -->
                        <p class="p-xl">
                            Jika Anda memiliki pertanyaan, silakan kirim pesan kepada kami dengan mengisi formulir di bawah
                            ini. Kami akan menghubungi Anda sesegera mungkin.
                        </p>

                    </div>
                </div>
            </div>


            <div class="row">


                <!-- CONTACT FORM -->
                <div class="col-md-7 col-lg-8">
                    <div class="form-holder pc-30 mb-40">
                        <form name="contactform" class="row contact-form" method="POST"
                            action="{{ route('contact.send') }}">
                            @csrf

                            <!-- Form Input -->
                            <div class="col-lg-12">
                                <input type="text" name="name" class="form-control name" placeholder="Nama Anda*"
                                    value="{{ old('name') }}" required>
                                @error('name')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Form Input -->
                            <div class="col-lg-6">
                                <input type="email" name="email" class="form-control email" placeholder="Alamat Email*"
                                    value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Phone Input -->
                            <div class="col-lg-6 ">
                                <input type="text" name="phone" class="form-control phone" placeholder="Nomor Telepon*"
                                    value="{{ old('phone') }}" required>
                                @error('phone')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Subject Input -->
                            <div class="col-lg-12 ">
                                <input type="text" name="subject" class="form-control subject"
                                    placeholder="Tentang apa ini?*" value="{{ old('subject') }}" required>
                                @error('subject')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Form Textarea -->
                            <div class="col-md-12 ">
                                <textarea name="message" class="form-control message" rows="6" placeholder="Pesan Anda ..." required>{{ old('message') }}</textarea>
                                @error('message')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Honeypot (hidden from humans, bots fill it) -->
                            <div style="position: absolute; left: -9999px;" aria-hidden="true">
                                <input type="text" name="website_url" tabindex="-1" autocomplete="off">
                            </div>

                            <!-- reCAPTCHA -->
                            <div class="col-md-12 mb-15">
                                {!! NoCaptcha::renderJs() !!}
                                {!! NoCaptcha::display() !!}
                                @error('g-recaptcha-response')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Form Button -->
                            <div class="col-md-12  text-right">
                                <button type="submit" class="btn btn-md btn-theme tra-grey-hover submit">Kirim
                                    Pesan</button>
                            </div>

                            <!-- Form Message -->
                            <div class="col-md-12 contact-form-msg text-center">
                                <div class="sending-msg"><span class="loading"></span></div>
                            </div>
                        </form>
                    </div>
                </div> <!-- END CONTACT FORM -->


                <!-- CONTACTS INFO -->
                <div class="col-md-5 col-lg-4">
                    <div class="contacts-info pc-30 mb-40">

                        <!-- LOCATION -->
                        <div class="contact-3-box mb-40 clearfix">
                            <h5 class="h5-xs">Lokasi Kami</h5>
                            <p class="grey-color">{{ $setting_web->address }}</p>
                        </div>

                        <!-- PHONES -->
                        <div class="contact-3-box mb-40 clearfix">
                            <h5 class="h5-xs">Informasi Kontak</h5>
                            <p class="grey-color"><span>Phone :</span> <a
                                    href="tel:{{ $setting_web->phone }}">{{ $setting_web->phone }}</a></p>
                            <p class="grey-color"><span>Email :</span> <a
                                    href="mailto:{{ $setting_web->email }}">{{ $setting_web->email }}</a></p>
                        </div>

                        <!-- WORKING HOURS -->
                        <div class="contact-3-box clearfix">
                            <h5 class="h5-xs">Jam Kerja</h5>
                            <p class="grey-color"><span>Setiap Hari : </span> 07:00 - 23:00</p>
                        </div>

                    </div>
                </div> <!-- END CONTACTS INFO -->


            </div> <!-- End row -->


        </div> <!-- End container -->
    </section> <!-- END CONTACTS-3 -->
    @include('front.partials.calll_to_action')
@endsection
@section('scripts')
@endsection
