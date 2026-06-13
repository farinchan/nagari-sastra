<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::post('/journal/store', [App\Http\Controllers\Api\JournalController::class, 'journalStore'])->name('journal.store');
    Route::post('/journal/sync', [App\Http\Controllers\Api\JournalController::class, 'journalSync'])->name('journal.sync');

    Route::get('/submissions/list', [App\Http\Controllers\Api\JournalController::class, 'submissionsList'])->name('submissions.list');
    Route::post('/submissions/select', [App\Http\Controllers\Api\JournalController::class, 'submissionsSelect'])->name('submissions.select');

    Route::get('/reviewer/list', [App\Http\Controllers\Api\JournalController::class, 'reviewerList'])->name('reviewer.list');
    Route::post('/reviewer/select', [App\Http\Controllers\Api\JournalController::class, 'reviewerSelect'])->name('reviewer.select');

    Route::get('/editor/list', [App\Http\Controllers\Api\JournalController::class, 'editorList'])->name('editor.list');
    Route::post('/editor/select', [App\Http\Controllers\Api\JournalController::class, 'editorSelect'])->name('editor.select');
    Route::get('/editor/get-editor', [App\Http\Controllers\Api\JournalController::class, 'editorGet'])->name('editor.get');

    Route::get('/journal/list', [App\Http\Controllers\Api\JournalController::class, 'journalList'])->name('journal.list');
    Route::post('/journal/get/{context_id}', [App\Http\Controllers\Api\JournalController::class, 'journalGet'])->name('journal.get');

    Route::get('/editor/list-cache', [App\Http\Controllers\Api\JournalController::class, 'editorListCache'])->name('editor.list.cache');
    Route::get('/reviewer/list-cache', [App\Http\Controllers\Api\JournalController::class, 'reviewerListCache'])->name('reviewer.list.cache');

    Route::get('/data/website', [App\Http\Controllers\Api\DataController::class, 'datawebsite'])->name('data.website');
    Route::get('/data/banner', [App\Http\Controllers\Api\DataController::class, 'dataBanner'])->name('data.banner');
    Route::get('/data/news', [App\Http\Controllers\Api\DataController::class, 'dataNews'])->name('data.news');

    Route::get('/data/journal', [App\Http\Controllers\Api\DataController::class, 'dataJournal'])->name('data.journal');
    Route::get('/data/journal/{context_id}', [App\Http\Controllers\Api\DataController::class, 'dataJournalContext'])->name('data.journal.context');

    Route::get('/data/issue/{journal_id}', [App\Http\Controllers\Api\DataController::class, 'dataIssue'])->name('data.issue');

    // Midtrans webhook for server-to-server callbacks
    Route::post('/payment/midtrans/callback', [App\Http\Controllers\Api\MidtransWebhookController::class, 'handle'])->name('payment.midtrans.callback');

    // Midtrans webhook for product orders
    Route::post('/payment/product/callback', [App\Http\Controllers\Front\ProductOrderController::class, 'callback'])->name('payment.product.callback');

});



// Webchat Widget API (public, no auth)
Route::prefix('webchat')->name('webchat.')->group(function () {
    Route::get('/embed.js', [App\Http\Controllers\Api\WebchatController::class, 'embedScript'])->name('embed');
    Route::get('/embed/{token}', [App\Http\Controllers\Api\WebchatController::class, 'embedScriptByPath'])->name('embed.token');
    Route::post('/start', [App\Http\Controllers\Api\WebchatController::class, 'startConversation'])->name('start')->middleware('throttle:30,1');
    Route::post('/send', [App\Http\Controllers\Api\WebchatController::class, 'sendMessage'])->name('send')->middleware('throttle:30,1');
    Route::post('/upload', [App\Http\Controllers\Api\WebchatController::class, 'uploadImage'])->name('upload')->middleware('throttle:10,1');
    Route::post('/fetch', [App\Http\Controllers\Api\WebchatController::class, 'fetchMessages'])->name('fetch')->middleware('throttle:60,1');
});

// Telegram Webhook (public, no auth)
Route::post('/telegram/webhook/{id}', [App\Http\Controllers\Back\CrmController::class, 'telegramWebhook'])->name('telegram.webhook');

// WhatsApp Official Webhook (Meta Cloud API)
Route::match(['get', 'post'], '/whatsapp/webhook/{id}', [App\Http\Controllers\Back\CrmController::class, 'waWebhook'])->name('whatsapp.webhook');

