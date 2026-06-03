<div class="hover-scroll-overlay-y mx-3 my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true"
    data-kt-scroll-height="auto"
    data-kt-scroll-dependencies="{default: '#kt_aside_toolbar, #kt_aside_footer', lg: '#kt_header, #kt_aside_toolbar, #kt_aside_footer'}"
    data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="5px">

    <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
        id="#kt_aside_menu" data-kt-menu="true">

        <div data-kt-menu-trigger="click" class="menu-item here show menu-accordion">
            <span class="menu-link">
                <span class="menu-icon">
                    <i class="ki-duotone ki-element-11 fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </i></span>
                <span class="menu-title">Dashboards</span>
                <span class="menu-arrow"></span>
            </span>
            <div class="menu-sub menu-sub-accordion">
                <div class="menu-item">
                    <a class="menu-link @if (request()->routeIs('back.dashboard')) active @endif"
                        href="{{ route('back.dashboard') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">Default</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link @if (request()->routeIs('back.dashboard.news')) active @endif"
                        href="{{ route('back.dashboard.news') }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">Berita</span>
                    </a>
                    @role('super-admin|keuangan')
                        <a class="menu-link @if (request()->routeIs('back.dashboard.cashflow')) active @endif"
                            href="{{ route('back.dashboard.cashflow') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Cashflow</span>
                        </a>
                    @endrole
                </div>
            </div>
        </div>

        @role('humas|super-admin')
            <div class="menu-item pt-5">
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">Post</span>
                </div>
            </div>

            <div class="menu-item">
                <a class="menu-link @if (request()->routeIs('back.announcement.index')) active @endif"
                    href="{{ route('back.announcement.index') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-information fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </span>
                    <span class="menu-title">Pengumuman</span>
                </a>
            </div>

            <div class= "menu-item">
                <a class="menu-link @if (request()->routeIs('back.event.*')) active @endif"
                    href=" {{ route('back.event.index') }} ">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-pin fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                    <span class="menu-title">Event</span>
                </a>
            </div>
            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion @if (request()->routeIs('back.news.*')) here show @endif">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-document fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                    <span class="menu-title">Berita</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link @if (request()->routeIs('back.news.category')) active @endif"
                            href="{{ route('back.news.category') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Kategori</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link @if (request()->routeIs('back.news.index')) active @endif"
                            href="{{ route('back.news.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">List Berita</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link @if (request()->routeIs('back.news.comment')) active @endif"
                            href="{{ route('back.news.comment') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Komentar</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class= "menu-item">
                <a class="menu-link @if (request()->routeIs('back.welcomeSpeech.index')) active @endif"
                    href="{{ route('back.welcomeSpeech.index') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-star fs-2"></i>
                    </span>
                    <span class="menu-title">Tentang kami</span>
                </a>
            </div>

            <div class= "menu-item">
                <a class="menu-link @if (request()->routeIs('back.testimonial.*')) active @endif"
                    href="{{ route('back.testimonial.index') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-message-text-2 fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </span>
                    <span class="menu-title">Testimonial</span>
                </a>
            </div>

            <div class= "menu-item">
                <a class="menu-link @if (request()->routeIs('back.menu.profil.*')) active @endif"
                    href="{{ route('back.menu.profil.index') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-burger-menu-5 fs-2"></i>
                    </span>
                    <span class="menu-title">Menu Profil</span>
                </a>
            </div>
        @endrole

        @role('editor|super-admin')
            <div class="menu-item pt-5">
                <div class="menu-content"><span class="menu-heading fw-bold text-uppercase fs-7">Buku</span>
                </div>
            </div>
            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion @if (request()->routeIs('back.book.*')) here show @endif">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-book fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                    </span>
                    <span class="menu-title">Buku</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link @if (request()->routeIs('back.book.category')) active @endif"
                            href="{{ route('back.book.category') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Kategori</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link @if (request()->routeIs('back.book.index') ||
                                request()->routeIs('back.book.create') ||
                                request()->routeIs('back.book.edit')) active @endif"
                            href="{{ route('back.book.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">List Buku</span>
                        </a>
                    </div>
                </div>
            </div>


            <div class="menu-item pt-5">
                <div class="menu-content"><span class="menu-heading fw-bold text-uppercase fs-7">Jurnal</span>
                </div>
            </div>
            @php
                $journal_all = App\Models\Journal::all();
            @endphp


            @foreach ($journal_all as $journal)
                @can($journal->url_path)
                    <div class="menu-item">
                        <a class="menu-link @if (request()->segment(3) == $journal->url_path) active @endif"
                            href="{{ route('back.journal.index', $journal->url_path) }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-note-2 fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </span>
                            <span class="menu-title">{{ $journal->name }}</span>
                        </a>
                    </div>
                @endcan
            @endforeach
        @endrole

        @role('keuangan|super-admin')
            <div class="menu-item pt-5">
                <div class="menu-content"><span class="menu-heading fw-bold text-uppercase fs-7">Keuangan</span>
                </div>
            </div>


            <div class="menu-item">
                <a class="menu-link @if (request()->routeIs('back.finance.invoice.index')) active @endif"
                    href="{{ route('back.finance.invoice.index') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-wallet fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                    </span>
                    <span class="menu-title">Invoice Management </span>
                </a>
            </div>
            <div class="menu-item">
                <a class="menu-link @if (request()->routeIs('back.finance.cashflow.index')) active @endif"
                    href="{{ route('back.finance.cashflow.index') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-wallet fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                    </span>
                    <span class="menu-title">CashFlow</span>
                </a>
            </div>


        @endrole

        <div class="menu-item pt-5">
                <div class="menu-content"><span class="menu-heading fw-bold text-uppercase fs-7">Laporan</span>
                </div>
            </div>
            <div class= "menu-item">
                <a class="menu-link @if (request()->routeIs('back.finance.report.index')) active @endif"
                    href="{{ route('back.finance.report.index') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-financial-schedule fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                    </span>
                    <span class="menu-title">Laporan Jurnal</span>
                </a>
            </div>


        @role('humas|super-admin')
            <div class="menu-item pt-5">
                <div class="menu-content"><span class="menu-heading fw-bold text-uppercase fs-7">Persuratan</span>
                </div>
            </div>

            <div class="menu-item">
                <a class="menu-link @if (request()->routeIs('back.incoming-mail.*')) active @endif"
                    href="{{ route('back.incoming-mail.index') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-sms fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                    <span class="menu-title">Surat Masuk</span>
                </a>
            </div>

            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion @if (request()->routeIs('back.outgoing-mail.*')) here show @endif">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-sms fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                    <span class="menu-title">Surat Keluar</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link @if (request()->routeIs('back.outgoing-mail.index') || request()->routeIs('back.outgoing-mail.create') || request()->routeIs('back.outgoing-mail.edit') || request()->routeIs('back.outgoing-mail.show')) active @endif"
                            href="{{ route('back.outgoing-mail.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Kelola Surat</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link @if (request()->routeIs('back.outgoing-mail.category*')) active @endif"
                            href="{{ route('back.outgoing-mail.category') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Kategori Surat</span>
                        </a>
                    </div>
                </div>
            </div>
        @endrole

        @role('super-admin')
            <div class="menu-item pt-5">
                <div class="menu-content"><span class="menu-heading fw-bold text-uppercase fs-7">CRM</span></div>
            </div>

            <div data-kt-menu-trigger="click" class="menu-item menu-accordion @if(request()->routeIs('back.crm.email.*')) here show @endif">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-sms fs-2"><span class="path1"></span><span class="path2"></span></i>
                    </span>
                    <span class="menu-title">Email</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link @if(request()->routeIs('back.crm.email.inbox') || request()->routeIs('back.crm.email.show') || request()->routeIs('back.crm.email.compose')) active @endif" href="{{ route('back.crm.email.inbox') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Inbox</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link @if(request()->routeIs('back.crm.email.accounts')) active @endif" href="{{ route('back.crm.email.accounts') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Akun Email</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link @if(request()->routeIs('back.crm.email.overview')) active @endif" href="{{ route('back.crm.email.overview') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Overview</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link @if(request()->routeIs('back.crm.email.groups') || request()->routeIs('back.crm.email.contacts')) active @endif" href="{{ route('back.crm.email.groups') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Grup Kontak</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link @if(request()->routeIs('back.crm.email.campaigns') || request()->routeIs('back.crm.email.campaigns.*')) active @endif" href="{{ route('back.crm.email.campaigns') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Marketing</span>
                        </a>
                    </div>
                </div>
            </div>

            <div data-kt-menu-trigger="click" class="menu-item menu-accordion @if(request()->routeIs('back.crm.telegram.*')) here show @endif">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-message-text-2 fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    </span>
                    <span class="menu-title">Telegram</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link @if(request()->routeIs('back.crm.telegram.chats') || request()->routeIs('back.crm.telegram.chats.*')) active @endif" href="{{ route('back.crm.telegram.chats') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Percakapan</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link @if(request()->routeIs('back.crm.telegram.bots')) active @endif" href="{{ route('back.crm.telegram.bots') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Kelola Bot</span>
                        </a>
                    </div>
                </div>
            </div>

            <div data-kt-menu-trigger="click" class="menu-item menu-accordion @if(request()->routeIs('back.crm.webchat.*')) here show @endif">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-message-programming fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                    </span>
                    <span class="menu-title">Webchat</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link @if(request()->routeIs('back.crm.webchat.index') || request()->routeIs('back.crm.webchat.show')) active @endif" href="{{ route('back.crm.webchat.index') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Percakapan</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link @if(request()->routeIs('back.crm.webchat.widgets')) active @endif" href="{{ route('back.crm.webchat.widgets') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Kelola Widget</span>
                        </a>
                    </div>
                </div>
            </div>

            <div data-kt-menu-trigger="click" class="menu-item menu-accordion @if(request()->routeIs('back.crm.chatery.*')) here show @endif">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-message-notif fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    </span>
                    <span class="menu-title">Whatsapp (Unofficial)</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link @if(request()->routeIs('back.crm.chatery.chats')) active @endif" href="{{ route('back.crm.chatery.chats') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Percakapan</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link @if(request()->routeIs('back.crm.chatery.bulk')) active @endif" href="{{ route('back.crm.chatery.bulk') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Bulk Message</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link @if(request()->routeIs('back.crm.chatery.groups')) active @endif" href="{{ route('back.crm.chatery.groups') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Grup Kontak</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link @if(request()->routeIs('back.crm.chatery.index')) active @endif" href="{{ route('back.crm.chatery.index') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Kelola Session</span>
                        </a>
                    </div>
                </div>
            </div>

            <div data-kt-menu-trigger="click" class="menu-item menu-accordion @if(request()->routeIs('back.crm.whatsapp.*')) here show @endif">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-whatsapp fs-2"><span class="path1"></span><span class="path2"></span></i>
                    </span>
                    <span class="menu-title">WhatsApp (Official)</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link @if(request()->routeIs('back.crm.whatsapp.chats')) active @endif" href="{{ route('back.crm.whatsapp.chats') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Percakapan</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link @if(request()->routeIs('back.crm.whatsapp.accounts')) active @endif" href="{{ route('back.crm.whatsapp.accounts') }}">
                            <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Kelola Akun</span>
                        </a>
                    </div>
                </div>
            </div>
        @endrole

        @role('super-admin')
            <div class="menu-item pt-5">
                <div class="menu-content"><span class="menu-heading fw-bold text-uppercase fs-7">Administrator</span>
                </div>
            </div>

            <div class="menu-item">
                <a class="menu-link @if (request()->routeIs('back.message.index')) active @endif"
                    href="{{ route('back.message.index') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-sms fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                    <span class="menu-title">Inbox</span>
                </a>
            </div>

            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion @if (request()->routeIs('back.master.*')) here show @endif">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-abstract-24 fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                    <span class="menu-title">Master Data</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link @if (request()->routeIs('back.master.journal.*')) active @endif"
                            href="{{ route('back.master.journal.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Journal</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link @if (request()->routeIs('back.master.user.*')) active @endif"
                            href="{{ route('back.master.user.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Pengguna</span>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a class="menu-link @if (request()->routeIs('back.master.payment-account.index')) active @endif"
                            href="{{ route('back.master.payment-account.index') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Payment Gateway</span>
                        </a>
                    </div>
                </div>
            </div>



            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion @if (request()->routeIs('back.setting.*')) here show @endif">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-setting-2 fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                    <span class="menu-title">Pengaturan</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item">
                        <a class="menu-link @if (request()->routeIs('back.setting.website')) active @endif"
                            href="{{ route('back.setting.website') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Website</span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a class="menu-link @if (request()->routeIs('back.setting.banner')) active @endif"
                            href="{{ route('back.setting.banner') }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">Banner</span>
                        </a>
                    </div>
                </div>
            </div>
        @endrole

    </div>

</div>
