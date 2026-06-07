<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Front\AccountController;
use App\Http\Controllers\Front\AnnouncementController;
use App\Http\Controllers\Front\BookController;
use App\Http\Controllers\Front\ContactController;
use App\Http\Controllers\Front\EventController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\JournalController;
use App\Http\Controllers\Front\MenuProfilController;
use App\Http\Controllers\Front\NewsController;
use App\Http\Controllers\Front\PaymentController;
use App\Http\Controllers\Front\TeamController;
use App\Http\Controllers\Front\OaiPmhController;
use App\Http\Controllers\Front\NewsletterController;
use App\Http\Controllers\Front\PageController;

use App\Http\Controllers\Back\DashboardController as BackDashboardController;
use App\Http\Controllers\Back\AnnouncementController as BackAnnouncementController;
use App\Http\Controllers\Back\EventController as BackEventController;
use App\Http\Controllers\Back\NewsController as BackNewsController;
use App\Http\Controllers\Back\journalController as BackJournalController;
use App\Http\Controllers\Back\BookController as BackBookController;
use App\Http\Controllers\Back\FinanceController as BackFinanceController;
use App\Http\Controllers\Back\MasterdataController as BackMasterDataController;
use App\Http\Controllers\Back\MenuProfilController as BackMenuProfilController;
use App\Http\Controllers\Back\UserController as BackUserController;
use App\Http\Controllers\Back\MessageController as BackMessageController;
use App\Http\Controllers\Back\SettingController as BackSettingController;
use App\Http\Controllers\Back\IncomingMailController as BackIncomingMailController;
use App\Http\Controllers\Back\OutgoingMailController as BackOutgoingMailController;
use App\Http\Controllers\Back\CrmController as BackCrmController;
use App\Http\Controllers\Back\TestimonialController as BackTestimonialController;
use App\Http\Controllers\Back\FaqController as BackFaqController;




// Route::get('/locale/{locale}', LocaleController::class)->name('locale.change');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/visit', [HomeController::class, 'vistWebsite'])->name('visit.ajax');

// OAI-PMH 2.0 Endpoint (Academic Harvesting: Google Scholar, BASE, WorldCat, OAPEN)
Route::get('/oai', [OaiPmhController::class, 'handle'])->name('oai-pmh');

// Sitemap XML (SEO)
Route::get('/sitemap.xml', function () {
    $news = \App\Models\News::where('status', 'published')->latest()->get();
    $journals = \App\Models\Journal::all();
    $books = \App\Models\Book::where('status', 'published')->latest()->get();
    $events = \App\Models\Event::where('is_active', true)->where('access', 'terbuka')->latest()->get();
    $announcements = \App\Models\Announcement::where('is_active', true)->latest()->get();
    $menuProfils = \App\Models\MenuProfil::all();

    $urls = [];

    // Static pages
    $urls[] = ['loc' => route('home'), 'changefreq' => 'daily', 'priority' => '1.0'];
    $urls[] = ['loc' => route('news.index'), 'changefreq' => 'daily', 'priority' => '0.8'];
    $urls[] = ['loc' => route('journal.index'), 'changefreq' => 'weekly', 'priority' => '0.8'];
    $urls[] = ['loc' => route('book.index'), 'changefreq' => 'weekly', 'priority' => '0.8'];
    $urls[] = ['loc' => route('event.index'), 'changefreq' => 'weekly', 'priority' => '0.8'];
    $urls[] = ['loc' => route('announcement.index'), 'changefreq' => 'weekly', 'priority' => '0.7'];
    $urls[] = ['loc' => route('contact.index'), 'changefreq' => 'monthly', 'priority' => '0.6'];
    $urls[] = ['loc' => route('page.faq'), 'changefreq' => 'monthly', 'priority' => '0.5'];
    $urls[] = ['loc' => route('page.terms'), 'changefreq' => 'yearly', 'priority' => '0.3'];
    $urls[] = ['loc' => route('page.privacy'), 'changefreq' => 'yearly', 'priority' => '0.3'];

    // Dynamic content
    foreach ($news as $item) {
        $urls[] = ['loc' => route('news.detail', $item->slug), 'lastmod' => $item->updated_at->toW3cString(), 'changefreq' => 'monthly', 'priority' => '0.7'];
    }
    foreach ($journals as $journal) {
        $urls[] = ['loc' => route('journal.detail', $journal->url_path), 'lastmod' => $journal->updated_at->toW3cString(), 'changefreq' => 'weekly', 'priority' => '0.8'];
    }
    foreach ($books as $book) {
        $urls[] = ['loc' => route('book.show', $book->slug), 'lastmod' => $book->updated_at->toW3cString(), 'changefreq' => 'monthly', 'priority' => '0.7'];
    }
    foreach ($events as $event) {
        $urls[] = ['loc' => route('event.show', $event->slug), 'lastmod' => $event->updated_at->toW3cString(), 'changefreq' => 'weekly', 'priority' => '0.7'];
    }
    foreach ($announcements as $announcement) {
        $urls[] = ['loc' => route('announcement.show', $announcement->slug), 'lastmod' => $announcement->updated_at->toW3cString(), 'changefreq' => 'monthly', 'priority' => '0.6'];
    }
    foreach ($menuProfils as $menu) {
        $urls[] = ['loc' => route('profil.show', $menu->slug), 'lastmod' => $menu->updated_at->toW3cString(), 'changefreq' => 'monthly', 'priority' => '0.5'];
    }

    // Build XML manually (avoids <?xml Blade parsing issue)
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($urls as $url) {
        $xml .= "  <url>\n";
        $xml .= "    <loc>" . htmlspecialchars($url['loc']) . "</loc>\n";
        if (!empty($url['lastmod'])) {
            $xml .= "    <lastmod>" . $url['lastmod'] . "</lastmod>\n";
        }
        $xml .= "    <changefreq>" . $url['changefreq'] . "</changefreq>\n";
        $xml .= "    <priority>" . $url['priority'] . "</priority>\n";
        $xml .= "  </url>\n";
    }
    $xml .= '</urlset>';

    return response($xml, 200)->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// Forgot Password Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

Route::prefix('account')->middleware('auth')->name('account.')->group(function () {
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/photo', [AccountController::class, 'updatePhoto'])->name('profile.photo');
    Route::get('/profile/password', [AccountController::class, 'password'])->name('password');
    Route::put('/profile/password/update', [AccountController::class, 'passwordUpdate'])->name('password.update');
});

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/syarat-ketentuan', [PageController::class, 'terms'])->name('page.terms');
Route::get('/kebijakan-privasi', [PageController::class, 'privacy'])->name('page.privacy');
Route::get('/faq', [PageController::class, 'faq'])->name('page.faq');

Route::prefix('event')->name('event.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/{slug}', [EventController::class, 'show'])->name('show');
    Route::post('/{slug}/register', [EventController::class, 'register'])->name('register');

    Route::get('/eticket/{uuid}', [EventController::class, 'eticket'])->name('eticket');

    Route::get('/presence/{code}', [EventController::class, 'presence'])->name('presence')->middleware('auth');
    Route::post('/presence/{code}/store', [EventController::class, 'presenceStore'])->name('presence.store')->middleware('auth');
});

Route::prefix('announcement')->name('announcement.')->group(function () {
    Route::get('/', [AnnouncementController::class, 'index'])->name('index');
    Route::get('/{slug}', [AnnouncementController::class, 'show'])->name('show');
});

Route::prefix('book')->name('book.')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('index');
    Route::get('/{slug}/preview', [BookController::class, 'preview'])->name('preview');
    Route::get('/{slug}', [BookController::class, 'show'])->name('show');
    Route::get('/category/{slug}', [BookController::class, 'category'])->name('category');
});

Route::prefix('profil')->name('profil.')->group(function () {
    // Route::get('/', [MenuProfilController::class, 'index'])->name('index');
    Route::get('/{slug}', [MenuProfilController::class, 'show'])->name('show');
});

Route::prefix('team')->name('team.')->group(function () {
    Route::get('/editor', [TeamController::class, 'editor'])->name('editor');
    Route::get('/reviewer', [TeamController::class, 'reviewer'])->name('reviewer');
});

Route::prefix('news')->name('news.')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('index');
    Route::get('/{slug}', [NewsController::class, 'detail'])->name('detail');

    Route::get('/category/{slug}', [NewsController::class, 'category'])->name('category');
    Route::post('/comment', [NewsController::class, 'comment'])->name('comment');

    Route::get('/visit/alt', [NewsController::class, 'visit'])->name('visit');
});

Route::prefix('journal')->name('journal.')->group(function () {
    Route::get('/', [JournalController::class, 'index'])->name('index');
    Route::get('/{journal_path}', [JournalController::class, 'detail'])->name('detail');
});

Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('index');
    Route::get('/{invoice_number}', [PaymentController::class, 'show'])->where('invoice_number', '[a-zA-Z0-9\-]+')->name('show');
});

Route::prefix('contact')->name('contact.')->group(function () {
    Route::get('/', [ContactController::class, 'index'])->name('index');
    Route::post('/', [ContactController::class, 'send'])->name('send');
});

Route::prefix('back')->name('back.')->middleware('auth')->group(function () {


    Route::get('/dashboard', [BackDashboardController::class, 'index'])->name('dashboard');
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/visitor-stat', [BackDashboardController::class, 'visistorStat'])->name('visitor.stat');

        Route::get('/news', [BackDashboardController::class, 'news'])->name('news');
        Route::get('/news-stat', [BackDashboardController::class, 'stat'])->name('news.stat');

        Route::get('/cashflow', [BackDashboardController::class, 'cashflow'])->name('cashflow');
        Route::get('/cashflow-stat', [BackDashboardController::class, 'cashflowStat'])->name('cashflow.stat');
    });

    Route::prefix('announcement')->name('announcement.')->group(function () {
        Route::get('/', [BackAnnouncementController::class, 'index'])->name('index');
        Route::get('/create', [BackAnnouncementController::class, 'create'])->name('create');
        Route::post('/create', [BackAnnouncementController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [BackAnnouncementController::class, 'edit'])->name('edit');
        Route::put('/edit/{id}', [BackAnnouncementController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [BackAnnouncementController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('book')->name('book.')->group(function () {
        Route::get('/category', [BackBookController::class, 'category'])->name('category');
        Route::post('/category', [BackBookController::class, 'categoryStore'])->name('category.store');
        Route::put('/category/edit/{id}', [BackBookController::class, 'categoryUpdate'])->name('category.update');
        Route::delete('/category/delete/{id}', [BackBookController::class, 'categoryDestroy'])->name('category.destroy');

        Route::get('/', [BackBookController::class, 'index'])->name('index');
        Route::get('/create', [BackBookController::class, 'create'])->name('create');
        Route::post('/create', [BackBookController::class, 'store'])->name('store');
        Route::put('/edit/{id}', [BackBookController::class, 'update'])->name('update');
        Route::put('/edit/{id}/files', [BackBookController::class, 'updateFiles'])->name('update.files');
        Route::delete('/delete/{id}', [BackBookController::class, 'destroy'])->name('destroy');

        // Detail tabs
        Route::get('/{id}/detail', [BackBookController::class, 'show'])->name('show');
        Route::get('/{id}/authors', [BackBookController::class, 'authorTab'])->name('authors');
        Route::get('/{id}/payment', [BackBookController::class, 'paymentTab'])->name('payment');

        // Editor
        Route::put('/{id}/editor', [BackBookController::class, 'editorUpdate'])->name('editor.update');

        // Authors
        Route::post('/{id}/author', [BackBookController::class, 'authorStore'])->name('author.store');
        Route::put('/{id}/author/{authorId}', [BackBookController::class, 'authorUpdate'])->name('author.update');
        Route::delete('/{id}/author/{authorId}', [BackBookController::class, 'authorDestroy'])->name('author.destroy');
        Route::get('/{id}/author/{authorId}/certificate', [BackBookController::class, 'authorCertificate'])->name('author.certificate');

        // Invoice
        Route::post('/{id}/invoice', [BackBookController::class, 'invoiceStore'])->name('invoice.store');
        Route::get('/invoice/{invoiceId}/download', [BackBookController::class, 'invoiceGenerate'])->name('invoice.download');
    });

    Route::prefix('event')->name('event.')->group(function () {
        Route::get('/', [BackEventController::class, 'index'])->name('index');
        Route::get('/create', [BackEventController::class, 'create'])->name('create');
        Route::post('/create', [BackEventController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [BackEventController::class, 'edit'])->name('edit');
        Route::put('/edit/{id}', [BackEventController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [BackEventController::class, 'destroy'])->name('destroy');

        Route::prefix('detail')->name('detail.')->group(function () {
            Route::get('/{id}/overview', [BackEventController::class, 'overview'])->name('overview');

            Route::get('/{id}/participant', [BackEventController::class, 'participant'])->name('participant');
            Route::post('/{id}/participant/store', [BackEventController::class, 'participantStore'])->name('participant.store');
            Route::delete('/{id}/participant/{event_user_id}/delete', [BackEventController::class, 'participantDestroy'])->name('participant.destroy');
            Route::get('/{id}/participant/export', [BackEventController::class, 'participantExport'])->name('participant.export');
            Route::get('/{id}/participant/import-reviewer', [BackEventController::class, 'participantImportReviewerModal'])->name('participant.import-reviewer.modal');
            Route::post('/{id}/participant/import-reviewer', [BackEventController::class, 'participantImportReviewer'])->name('participant.import-reviewer');
            Route::get('/{id}/participant/import-editor', [BackEventController::class, 'participantImportEditorModal'])->name('participant.import-editor.modal');
            Route::post('/{id}/participant/import-editor', [BackEventController::class, 'participantImportEditor'])->name('participant.import-editor');

            Route::get('/{id}/attendance', [BackEventController::class, 'attendance'])->name('attendance');
            Route::post('/{id}/attendance/store', [BackEventController::class, 'attendanceStore'])->name('attendance.store');
            Route::put('/{id}/attendance/{event_attendance_id}/update', [BackEventController::class, 'attendanceUpdate'])->name('attendance.update');
            Route::get('/{id}/attendance/{event_attendance_id}', [BackEventController::class, 'attendanceDetail'])->name('attendance.detail');
            Route::get('/{id}/attendance/{event_attendance_id}/datatable', [BackEventController::class, 'attendanceDetailDatatable'])->name('attendance.detail.datatable');
            Route::post('/{id}/attendance/{event_attendance_id}/checkin/{event_user_id}', [BackEventController::class, 'attendanceDetailUserCheckin'])->name('attendance.detail.checkin');
            Route::get('/{id}/attendance/{event_attendance_id}/export', [BackEventController::class, 'attendanceExport'])->name('attendance.export');

            Route::get('/{id}/notification', [BackEventController::class, 'notification'])->name('notification');
            Route::post('/{id}/notification/whatsapp', [BackEventController::class, 'notificationWhatsapp'])->name('notification.whatsapp');
        });
    });

    Route::prefix('news')->name('news.')->group(function () {
        Route::get('/category', [BackNewsController::class, 'category'])->name('category');
        Route::post('/category', [BackNewsController::class, 'categoryStore'])->name('category.store');
        Route::put('/category/edit/{id}', [BackNewsController::class, 'categoryUpdate'])->name('category.update');
        Route::delete('/category/delete/{id}', [BackNewsController::class, 'categoryDestroy'])->name('category.destroy');

        Route::get('/', [BackNewsController::class, 'index'])->name('index');
        Route::get('/create', [BackNewsController::class, 'create'])->name('create');
        Route::post('/create', [BackNewsController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [BackNewsController::class, 'edit'])->name('edit');
        Route::put('/edit/{id}', [BackNewsController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [BackNewsController::class, 'destroy'])->name('destroy');

        Route::get('/comment', [BackNewsController::class, 'comment'])->name('comment');
        Route::post('/comment/spam/{id}', [BackNewsController::class, 'commentSpam'])->name('comment.spam');
    });


    Route::prefix('testimonial')->name('testimonial.')->group(function () {
        Route::get('/', [BackTestimonialController::class, 'index'])->name('index');
        Route::post('/', [BackTestimonialController::class, 'store'])->name('store');
        Route::put('/edit/{id}', [BackTestimonialController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [BackTestimonialController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('faq')->name('faq.')->group(function () {
        Route::get('/', [BackFaqController::class, 'index'])->name('index');
        Route::post('/', [BackFaqController::class, 'store'])->name('store');
        Route::put('/edit/{id}', [BackFaqController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [BackFaqController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('menu')->name('menu.')->group(function () {


            Route::prefix('profil')->name('profil.')->group(function () {
                Route::get('/', [BackMenuProfilController::class, 'index'])->name('index');
                Route::post('/create', [BackMenuProfilController::class, 'store'])->name('store');
                Route::get('/edit/{id}', [BackMenuProfilController::class, 'edit'])->name('edit');
                Route::put('/edit/{id}', [BackMenuProfilController::class, 'update'])->name('update');
                Route::delete('/delete/{id}', [BackMenuProfilController::class, 'destroy'])->name('destroy');

                Route::post('/upload', [BackMenuProfilController::class, 'upload'])->name('upload');
            });
        });

    Route::prefix('journal')->name('journal.')->group(function () {
        Route::get('/{journal_path}', [BackJournalController::class, 'index'])->name('index');

        Route::post('/{journal_path}/issue/store', [BackJournalController::class, 'issueStore'])->name('issue.store');
        Route::put('/{journal_path}/issue/{issue_id}/update', [BackJournalController::class, 'issueUpdate'])->name('issue.update');
        Route::delete('/{journal_path}/issue/{issue_id}/delete', [BackJournalController::class, 'issueDestroy'])->name('issue.destroy');

        Route::get('/{journal_path}/issue/{issue_id}/dashboard', [BackJournalController::class, 'dashboardIndex'])->name('dashboard.index');

        Route::get('/{journal_path}/issue/{issue_id}/article', [BackJournalController::class, 'articleIndex'])->name('article.index');
        Route::put('/{journal_path}/issue/{issue_id}/article/{id}/update', [BackJournalController::class, 'articleUpdate'])->name('article.update');
        Route::delete('/{journal_path}/issue/{issue_id}/article/{id}/destroy', [BackJournalController::class, 'articleDestroy'])->name('article.destroy');
        Route::get('/{journal_path}/issue/{issue_id}/article-export', [BackJournalController::class, 'articleExport'])->name('article.export');
        Route::get('/loa/submission/{id}/generate', [BackJournalController::class, 'loaGenerate'])->name('loa.generate');
        Route::get('/loa/submission/{id}/mail-send', [BackJournalController::class, 'loaMailSend'])->name('loa.mail-send');
        Route::get('/invoice/submission/{id}/generate', [BackJournalController::class, 'invoiceGenerate'])->name('invoice.generate');
        Route::get('/invoice/submission/{id}/mail-send', [BackJournalController::class, 'invoiceMailSend'])->name('invoice.mail-send');
        Route::post('/invoice/submission/{id}/custom', [BackJournalController::class, 'invoiceCustomStore'])->name('invoice.custom.store');
        Route::get('/invoice/custom/{invoice_id}/generate-custom', [BackJournalController::class, 'invoiceGenerateCustom'])->name('invoice.custom.generate');
        Route::get('/invoice/custom/{invoice_id}/mail-send-custom', [BackJournalController::class, 'invoiceMailSendCustom'])->name('invoice.custom.mail-send');

        Route::get('/{journal_path}/issue/{issue_id}/setting', [BackJournalController::class, 'settingIndex'])->name('setting.index');
    });


    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/report', [BackFinanceController::class, 'reportIndex'])->name('report.index');
        Route::get('/report/datatable', [BackFinanceController::class, 'reportDatatable'])->name('report.datatable');
        Route::get('/report/export', [BackFinanceController::class, 'reportExport'])->name('report.export');

        Route::get('/cashflow', [BackFinanceController::class, 'cashflowIndex'])->name('cashflow.index');
        Route::get('/cashflow/datatable', [BackFinanceController::class, 'cashflowDatatables'])->name('cashflow.datatable');
        Route::get('/cashflow/export', [BackFinanceController::class, 'cashflowExport'])->name('cashflow.export');
        Route::post('/cashflow/store', [BackFinanceController::class, 'cashflowStore'])->name('cashflow.store');
        Route::put('/cashflow/{id}/update', [BackFinanceController::class, 'cashflowUpdate'])->name('cashflow.update');
        Route::get('/cashflow/{id}/delete', [BackFinanceController::class, 'cashflowDestroy'])->name('cashflow.destroy');

        Route::get('/invoice', [BackFinanceController::class, 'invoiceIndex'])->name('invoice.index');
        Route::get('/invoice/datatable', [BackFinanceController::class, 'invoiceDatatable'])->name('invoice.datatable');
        Route::get('/invoice/export', [BackFinanceController::class, 'invoiceExport'])->name('invoice.export');
        Route::get('/invoice/create', [BackFinanceController::class, 'invoiceCreate'])->name('invoice.create');
        Route::post('/invoice/store', [BackFinanceController::class, 'invoiceStore'])->name('invoice.store');
        Route::get('/invoice/{id}/show', [BackFinanceController::class, 'invoiceShow'])->name('invoice.show');
        Route::post('/invoice/{id}/confirm', [BackFinanceController::class, 'invoiceConfirm'])->name('invoice.confirm');
        Route::delete('/invoice/{id}/destroy', [BackFinanceController::class, 'invoiceDestroy'])->name('invoice.destroy');
    });

    Route::prefix('master')->name('master.')->group(function () {

        Route::prefix('journal')->name('journal.')->group(function () {
            Route::get('/', [BackMasterDataController::class, 'journalIndex'])->name('index');
            Route::put('/edit/{id}', [BackMasterDataController::class, 'journalUpdate'])->name('update');
            Route::delete('/delete/{id}', [BackMasterDataController::class, 'journalDestroy'])->name('destroy');
        });

        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/', [BackUserController::class, 'index'])->name('index');
            Route::get('/create', [BackUserController::class, 'create'])->name('create');
            Route::post('/create', [BackUserController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [BackUserController::class, 'edit'])->name('edit');
            Route::put('/edit/{id}', [BackUserController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [BackUserController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('reviewer')->name('reviewer.')->group(function () {
            Route::get('/', [BackMasterDataController::class, 'reviewerIndex'])->name('index');
            Route::put('/edit/{reviewer_id}', [BackMasterDataController::class, 'reviewerUpdate'])->name('update');
            Route::get('/export', [BackMasterDataController::class, 'reviewerExport'])->name('export');
            Route::post('/sync-to-user', [BackMasterDataController::class, 'reviewerSyncToUser'])->name('sync-to-user');

        });


        Route::prefix('payment-account')->name('payment-account.')->group(function () {
            Route::get('/', [BackMasterDataController::class, 'paymentAccount'])->name('index');
            Route::put('/update', [BackMasterDataController::class, 'paymentAccountUpdate'])->name('update');
        });
    });

    Route::prefix('message')->name('message.')->group(function () {
        Route::get('/', [BackMessageController::class, 'index'])->name('index');
        Route::delete('/{id}', [BackMessageController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('setting')->name('setting.')->group(function () {
        Route::get('/website', [BackSettingController::class, 'website'])->name('website');
        Route::put('/website', [BackSettingController::class, 'websiteUpdate'])->name('website.update');
        Route::put('/website/info', [BackSettingController::class, 'informationUpdate'])->name('website.info');

        Route::get('/banner', [BackSettingController::class, 'banner'])->name('banner');
        Route::put('/banner/{id}/update', [BackSettingController::class, 'bannerUpdate'])->name('banner-update');
    });



    // Route::prefix('email')->name('email.')->group(function () {
    //     Route::post('/send-mail', [EmailController::class, 'sendEmail'])->name('send-mail');
    //     Route::post('/send-multi-mail', [EmailController::class, 'sendEmailMultiple'])->name('send-multi-mail');
    // });

    Route::prefix('incoming-mail')->name('incoming-mail.')->group(function () {
        Route::get('/', [BackIncomingMailController::class, 'index'])->name('index');
        Route::get('/create', [BackIncomingMailController::class, 'create'])->name('create');
        Route::post('/create', [BackIncomingMailController::class, 'store'])->name('store');
        Route::get('/show/{id}', [BackIncomingMailController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [BackIncomingMailController::class, 'edit'])->name('edit');
        Route::put('/edit/{id}', [BackIncomingMailController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [BackIncomingMailController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('outgoing-mail')->name('outgoing-mail.')->group(function () {
        Route::get('/category', [BackOutgoingMailController::class, 'category'])->name('category');
        Route::post('/category', [BackOutgoingMailController::class, 'categoryStore'])->name('category.store');
        Route::put('/category/edit/{id}', [BackOutgoingMailController::class, 'categoryUpdate'])->name('category.update');
        Route::delete('/category/delete/{id}', [BackOutgoingMailController::class, 'categoryDestroy'])->name('category.destroy');

        Route::get('/', [BackOutgoingMailController::class, 'index'])->name('index');
        Route::get('/create', [BackOutgoingMailController::class, 'create'])->name('create');
        Route::post('/create', [BackOutgoingMailController::class, 'store'])->name('store');
        Route::get('/show/{id}', [BackOutgoingMailController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [BackOutgoingMailController::class, 'edit'])->name('edit');
        Route::put('/edit/{id}', [BackOutgoingMailController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [BackOutgoingMailController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('crm')->name('crm.')->group(function () {
        // Email Accounts
        Route::get('/email/accounts', [BackCrmController::class, 'emailAccountIndex'])->name('email.accounts');
        Route::post('/email/accounts/store', [BackCrmController::class, 'emailAccountStore'])->name('email.accounts.store');
        Route::put('/email/accounts/{id}/update', [BackCrmController::class, 'emailAccountUpdate'])->name('email.accounts.update');
        Route::delete('/email/accounts/{id}/destroy', [BackCrmController::class, 'emailAccountDestroy'])->name('email.accounts.destroy');
        Route::post('/email/accounts/test', [BackCrmController::class, 'emailAccountTest'])->name('email.accounts.test');

        // Email Inbox
        Route::get('/email/inbox', [BackCrmController::class, 'emailInbox'])->name('email.inbox');
        Route::get('/email/show/{uid}', [BackCrmController::class, 'emailShow'])->name('email.show');
        Route::get('/email/compose', [BackCrmController::class, 'emailCompose'])->name('email.compose');
        Route::post('/email/send', [BackCrmController::class, 'emailSend'])->name('email.send');
        Route::post('/email/delete', [BackCrmController::class, 'emailDelete'])->name('email.delete');
        Route::post('/email/sync', [BackCrmController::class, 'emailSync'])->name('email.sync');

        // Email Overview
        Route::get('/email/overview', [BackCrmController::class, 'emailOverview'])->name('email.overview');

        // Email Groups & Contacts
        Route::get('/email/groups', [BackCrmController::class, 'emailGroupIndex'])->name('email.groups');
        Route::post('/email/groups/store', [BackCrmController::class, 'emailGroupStore'])->name('email.groups.store');
        Route::put('/email/groups/{id}/update', [BackCrmController::class, 'emailGroupUpdate'])->name('email.groups.update');
        Route::delete('/email/groups/{id}/destroy', [BackCrmController::class, 'emailGroupDestroy'])->name('email.groups.destroy');
        Route::get('/email/groups/{groupId}/contacts', [BackCrmController::class, 'emailContactIndex'])->name('email.contacts');
        Route::post('/email/contacts/store', [BackCrmController::class, 'emailContactStore'])->name('email.contacts.store');
        Route::delete('/email/contacts/{id}/destroy', [BackCrmController::class, 'emailContactDestroy'])->name('email.contacts.destroy');
        Route::post('/email/contacts/import', [BackCrmController::class, 'emailContactImport'])->name('email.contacts.import');

        // Email Campaigns
        Route::get('/email/campaigns', [BackCrmController::class, 'emailCampaignIndex'])->name('email.campaigns');
        Route::get('/email/campaigns/create', [BackCrmController::class, 'emailCampaignCreate'])->name('email.campaigns.create');
        Route::post('/email/campaigns/store', [BackCrmController::class, 'emailCampaignStore'])->name('email.campaigns.store');
        Route::post('/email/campaigns/send', [BackCrmController::class, 'emailCampaignSend'])->name('email.campaigns.send');
        Route::delete('/email/campaigns/{id}/destroy', [BackCrmController::class, 'emailCampaignDestroy'])->name('email.campaigns.destroy');

        // Telegram Bot
        Route::get('/telegram/bots', [BackCrmController::class, 'telegramBotIndex'])->name('telegram.bots');
        Route::post('/telegram/bots/store', [BackCrmController::class, 'telegramBotStore'])->name('telegram.bots.store');
        Route::put('/telegram/bots/{id}/update', [BackCrmController::class, 'telegramBotUpdate'])->name('telegram.bots.update');
        Route::delete('/telegram/bots/{id}/destroy', [BackCrmController::class, 'telegramBotDestroy'])->name('telegram.bots.destroy');
        Route::post('/telegram/bots/set-webhook', [BackCrmController::class, 'telegramBotSetWebhook'])->name('telegram.bots.set-webhook');
        Route::post('/telegram/bots/unset-webhook', [BackCrmController::class, 'telegramBotUnsetWebhook'])->name('telegram.bots.unset-webhook');

        // Telegram Chats
        Route::get('/telegram/chats', [BackCrmController::class, 'telegramChats'])->name('telegram.chats');
        Route::get('/telegram/chats/{id}', [BackCrmController::class, 'telegramChatShow'])->name('telegram.chats.show');
        Route::post('/telegram/send-message', [BackCrmController::class, 'telegramSendMessage'])->name('telegram.send-message');
        Route::get('/telegram/file/{botId}/{fileId}', [BackCrmController::class, 'telegramFileProxy'])->name('telegram.file-proxy');

        // WhatsApp Unofficial (Chatery)
        Route::get('/chatery', [BackCrmController::class, 'chateryIndex'])->name('chatery.index');
        Route::post('/chatery', [BackCrmController::class, 'chateryStore'])->name('chatery.store');
        Route::get('/chatery/chats', [BackCrmController::class, 'chateryChats'])->name('chatery.chats');
        Route::post('/chatery/chats/send', [BackCrmController::class, 'chaterySendChatMessage'])->name('chatery.chats.send');
        Route::get('/chatery/api/chats', [BackCrmController::class, 'chateryApiChats'])->name('chatery.api.chats');
        Route::get('/chatery/api/messages', [BackCrmController::class, 'chateryApiMessages'])->name('chatery.api.messages');
        Route::get('/chatery/bulk', [BackCrmController::class, 'chateryBulk'])->name('chatery.bulk');
        Route::post('/chatery/bulk/send', [BackCrmController::class, 'chateryBulkSend'])->name('chatery.bulk.send');
        Route::get('/chatery/groups', [BackCrmController::class, 'chateryGroups'])->name('chatery.groups');
        Route::post('/chatery/groups', [BackCrmController::class, 'chateryGroupStore'])->name('chatery.groups.store');
        Route::put('/chatery/groups/{id}', [BackCrmController::class, 'chateryGroupUpdate'])->name('chatery.groups.update');
        Route::delete('/chatery/groups/{id}', [BackCrmController::class, 'chateryGroupDestroy'])->name('chatery.groups.destroy');
        Route::get('/chatery/groups/{id}/contacts', [BackCrmController::class, 'chateryGroupContacts'])->name('chatery.groups.contacts');
        Route::get('/chatery/groups/{id}/phones', [BackCrmController::class, 'chateryGroupPhones'])->name('chatery.groups.phones');
        Route::post('/chatery/contacts', [BackCrmController::class, 'chateryContactStore'])->name('chatery.contacts.store');
        Route::delete('/chatery/contacts/{id}', [BackCrmController::class, 'chateryContactDestroy'])->name('chatery.contacts.destroy');
        Route::put('/chatery/{id}', [BackCrmController::class, 'chateryUpdate'])->name('chatery.update');
        Route::delete('/chatery/{id}', [BackCrmController::class, 'chateryDestroy'])->name('chatery.destroy');
        Route::post('/chatery/{id}/connect', [BackCrmController::class, 'chateryConnect'])->name('chatery.connect');
        Route::post('/chatery/{id}/disconnect', [BackCrmController::class, 'chateryDisconnect'])->name('chatery.disconnect');
        Route::get('/chatery/{id}/status', [BackCrmController::class, 'chateryCheckStatus'])->name('chatery.status');

        // WhatsApp Official
        Route::get('/whatsapp/accounts', [BackCrmController::class, 'waAccountIndex'])->name('whatsapp.accounts');
        Route::post('/whatsapp/accounts', [BackCrmController::class, 'waAccountStore'])->name('whatsapp.accounts.store');
        Route::put('/whatsapp/accounts/{id}', [BackCrmController::class, 'waAccountUpdate'])->name('whatsapp.accounts.update');
        Route::delete('/whatsapp/accounts/{id}', [BackCrmController::class, 'waAccountDestroy'])->name('whatsapp.accounts.destroy');
        Route::get('/whatsapp/chats', [BackCrmController::class, 'waChats'])->name('whatsapp.chats');
        Route::get('/whatsapp/chats/{id}', [BackCrmController::class, 'waChatShow'])->name('whatsapp.chats.show');
        Route::post('/whatsapp/send-message', [BackCrmController::class, 'waSendMessage'])->name('whatsapp.send-message');
        Route::post('/whatsapp/send-template', [BackCrmController::class, 'waSendTemplate'])->name('whatsapp.send-template');
        Route::get('/whatsapp/media/{accountId}/{mediaId}', [BackCrmController::class, 'waMediaProxy'])->name('whatsapp.media-proxy');

        // Webchat
        Route::get('/webchat', [BackCrmController::class, 'webchatIndex'])->name('webchat.index');
        Route::get('/webchat/widgets', [BackCrmController::class, 'webchatWidgetIndex'])->name('webchat.widgets');
        Route::post('/webchat/widgets/store', [BackCrmController::class, 'webchatWidgetStore'])->name('webchat.widgets.store');
        Route::put('/webchat/widgets/{id}/update', [BackCrmController::class, 'webchatWidgetUpdate'])->name('webchat.widgets.update');
        Route::delete('/webchat/widgets/{id}/destroy', [BackCrmController::class, 'webchatWidgetDestroy'])->name('webchat.widgets.destroy');
        Route::get('/webchat/{id}', [BackCrmController::class, 'webchatShow'])->name('webchat.show');
        Route::post('/webchat/{id}/reply', [BackCrmController::class, 'webchatReply'])->name('webchat.reply');
        Route::post('/webchat/{id}/close', [BackCrmController::class, 'webchatClose'])->name('webchat.close');
        Route::delete('/webchat/{id}/destroy', [BackCrmController::class, 'webchatDestroy'])->name('webchat.destroy');
        Route::get('/webchat/{id}/fetch', [BackCrmController::class, 'webchatFetchNew'])->name('webchat.fetch');
        Route::post('/webchat/{id}/reply-ajax', [BackCrmController::class, 'webchatReplyAjax'])->name('webchat.reply-ajax');
    });
});

