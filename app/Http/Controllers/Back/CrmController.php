<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\EmailAccount;
use App\Models\EmailCampaign;
use App\Models\EmailCampaignLog;
use App\Models\EmailContact;
use App\Models\EmailGroup;
use App\Models\EmailMessage;
use App\Models\TelegramBot;
use App\Models\TelegramChat;
use App\Models\TelegramMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Webklex\IMAP\Facades\Client as ImapClient;
use Carbon\Carbon;
use App\Events\NewCrmMessage;

class CrmController extends Controller
{
    // ==========================================
    // EMAIL ACCOUNTS CRUD
    // ==========================================

    public function emailAccountIndex()
    {
        $accounts = EmailAccount::orderBy('is_default', 'desc')->orderBy('name', 'asc')->get();
        return view('back.pages.crm.email.accounts', compact('accounts'));
    }

    public function emailAccountStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:email_accounts,email',
            'imap_host' => 'required|string',
            'imap_port' => 'required|string',
            'imap_encryption' => 'required|in:ssl,tls,none',
            'imap_username' => 'required|string',
            'imap_password' => 'required|string',
            'smtp_host' => 'required|string',
            'smtp_port' => 'required|string',
            'smtp_encryption' => 'required|in:ssl,tls,none',
            'smtp_username' => 'required|string',
            'smtp_password' => 'required|string',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->first());
            return redirect()->back()->withInput();
        }

        if ($request->has('is_default') && $request->is_default) {
            EmailAccount::where('is_default', true)->update(['is_default' => false]);
        }

        EmailAccount::create([
            'name' => $request->name,
            'email' => $request->email,
            'imap_host' => $request->imap_host,
            'imap_port' => $request->imap_port,
            'imap_encryption' => $request->imap_encryption,
            'imap_username' => $request->imap_username,
            'imap_password' => $request->imap_password,
            'smtp_host' => $request->smtp_host,
            'smtp_port' => $request->smtp_port,
            'smtp_encryption' => $request->smtp_encryption,
            'smtp_username' => $request->smtp_username,
            'smtp_password' => $request->smtp_password,
            'is_active' => $request->has('is_active') ? true : false,
            'is_default' => $request->has('is_default') ? true : false,
            'created_by' => Auth::user()->id,
        ]);

        Alert::success('Berhasil', 'Akun email berhasil ditambahkan.');
        return redirect()->route('back.crm.email.accounts');
    }

    public function emailAccountUpdate(Request $request, $id)
    {
        $account = EmailAccount::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:email_accounts,email,' . $id,
            'imap_host' => 'required|string',
            'imap_port' => 'required|string',
            'imap_encryption' => 'required|in:ssl,tls,none',
            'imap_username' => 'required|string',
            'smtp_host' => 'required|string',
            'smtp_port' => 'required|string',
            'smtp_encryption' => 'required|in:ssl,tls,none',
            'smtp_username' => 'required|string',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->first());
            return redirect()->back()->withInput();
        }

        if ($request->has('is_default') && $request->is_default) {
            EmailAccount::where('is_default', true)->where('id', '!=', $id)->update(['is_default' => false]);
        }

        $account->name = $request->name;
        $account->email = $request->email;
        $account->imap_host = $request->imap_host;
        $account->imap_port = $request->imap_port;
        $account->imap_encryption = $request->imap_encryption;
        $account->imap_username = $request->imap_username;
        $account->smtp_host = $request->smtp_host;
        $account->smtp_port = $request->smtp_port;
        $account->smtp_encryption = $request->smtp_encryption;
        $account->smtp_username = $request->smtp_username;
        $account->is_active = $request->has('is_active') ? true : false;
        $account->is_default = $request->has('is_default') ? true : false;

        if ($request->filled('imap_password')) {
            $account->imap_password = $request->imap_password;
        }
        if ($request->filled('smtp_password')) {
            $account->smtp_password = $request->smtp_password;
        }

        $account->save();

        Alert::success('Berhasil', 'Akun email berhasil diperbarui.');
        return redirect()->route('back.crm.email.accounts');
    }

    public function emailAccountDestroy($id)
    {
        $account = EmailAccount::findOrFail($id);
        $account->delete(); // cascade deletes email_messages
        Alert::success('Berhasil', 'Akun email berhasil dihapus.');
        return redirect()->route('back.crm.email.accounts');
    }

    public function emailAccountTest(Request $request)
    {
        $account = EmailAccount::findOrFail($request->account_id);
        $results = ['imap' => false, 'smtp' => false, 'imap_error' => '', 'smtp_error' => ''];

        try {
            $client = $this->getImapClient($account);
            $client->connect();
            $results['imap'] = true;
            $client->disconnect();
        } catch (\Exception $e) {
            $results['imap_error'] = 'IMAP: ' . $e->getMessage();
        }

        try {
            $fp = @fsockopen($account->smtp_host, (int) $account->smtp_port, $errno, $errstr, 10);
            if ($fp) {
                $results['smtp'] = true;
                fclose($fp);
            } else {
                $results['smtp_error'] = "SMTP: Tidak dapat terhubung ($errstr)";
            }
        } catch (\Exception $e) {
            $results['smtp_error'] = 'SMTP: ' . $e->getMessage();
        }

        return response()->json($results);
    }

    // ==========================================
    // IMAP CLIENT HELPER
    // ==========================================

    private function getImapClient(EmailAccount $account): \Webklex\PHPIMAP\Client
    {
        $config = $account->getImapConfig();

        return ImapClient::make([
            'host'          => $config['host'],
            'port'          => (int) $config['port'],
            'encryption'    => $config['encryption'] === 'none' ? false : $config['encryption'],
            'validate_cert' => false,
            'username'      => $config['username'],
            'password'      => $config['password'],
            'protocol'      => 'imap',
            'authentication' => null,
            'timeout'       => 30,
        ]);
    }

    // ==========================================
    // SYNC: Incremental — only fetch new emails
    // ==========================================

    public function emailSync(Request $request)
    {
        set_time_limit(300);

        $account = EmailAccount::findOrFail($request->account_id);
        $folder = $request->get('folder', 'INBOX');

        try {
            $client = $this->getImapClient($account);
            $client->connect();

            // Fetch and save folder list from IMAP
            try {
                $imapFolders = $client->getFolders(false);
                $folderList = [];
                foreach ($imapFolders as $f) {
                    $folderList[] = [
                        'name' => $f->name,
                        'full_name' => $f->full_name,
                        'path' => $f->path,
                    ];
                }
                $account->imap_folders = $folderList;
                $account->save();
            } catch (\Exception $e) {
                // Folder fetch failed — continue with sync
            }

            $imapFolder = $client->getFolder($folder);
            if (!$imapFolder) {
                $client->disconnect();
                return response()->json(['success' => false, 'error' => 'Folder "' . $folder . '" tidak ditemukan']);
            }

            // Get existing UIDs to skip duplicates
            $existingUids = EmailMessage::forAccount($account->id)
                ->forFolder($folder)
                ->pluck('uid')
                ->toArray();

            // Build query — use SINCE date for incremental sync
            $query = $imapFolder->messages();

            if (!empty($existingUids) && $account->last_synced_at) {
                // Incremental: fetch emails since last sync date (minus 1 day buffer)
                $sinceDate = $account->last_synced_at->subDay()->format('d-M-Y');
                $query = $query->since($sinceDate);
            } else {
                // First sync: fetch all, limit handled below
                $query = $query->all();
            }

            $messages = $query
                ->setFetchBody(true)
                ->setFetchFlags(true)
                ->setFetchOrder('desc')
                ->limit(50)
                ->get();

            $synced = 0;

            foreach ($messages as $message) {
                try {
                    $uid = $message->getUid();

                    // Skip if already exists (in-memory check — fast)
                    if (in_array($uid, $existingUids)) {
                        continue;
                    }

                    // Parse from
                    $fromName = '';
                    $fromEmail = '';
                    $from = $message->getFrom();
                    if ($from && $from->first()) {
                        $addr = $from->first();
                        $fromName = $addr->personal ?: '';
                        $fromEmail = $addr->mail ?? '';
                    }

                    // Parse to
                    $toList = [];
                    $to = $message->getTo();
                    if ($to && $to->first()) {
                        foreach ($to->toArray() as $addr) {
                            $toList[] = $addr->mail ?? '';
                        }
                    }

                    // Parse cc
                    $ccList = [];
                    $cc = $message->getCc();
                    if ($cc && $cc->first()) {
                        foreach ($cc->toArray() as $addr) {
                            $ccList[] = $addr->mail ?? '';
                        }
                    }

                    // Subject
                    $subject = '(Tanpa Subjek)';
                    $subj = $message->getSubject();
                    if ($subj) $subject = $subj->toString() ?: '(Tanpa Subjek)';

                    // Date
                    $emailDate = null;
                    $d = $message->getDate();
                    if ($d) {
                        try { $emailDate = Carbon::parse($d->first()); } catch (\Exception $e) {}
                    }

                    // Body
                    $bodyHtml = $message->getHTMLBody() ?: '';
                    $bodyText = $message->getTextBody() ?: '';

                    // Flags
                    $isSeen = false;
                    $flags = $message->getFlags();
                    if ($flags) {
                        $flagArr = $flags->toArray();
                        $isSeen = in_array('Seen', $flagArr) || in_array('\Seen', $flagArr);
                    }

                    // Attachments
                    $hasAttachment = $message->hasAttachments();

                    // Message ID
                    $messageId = '';
                    $mid = $message->getMessageId();
                    if ($mid) $messageId = $mid->toString() ?: '';

                    EmailMessage::create([
                        'email_account_id' => $account->id,
                        'uid' => $uid,
                        'message_id' => $messageId,
                        'folder' => $folder,
                        'from_name' => mb_substr($fromName, 0, 255),
                        'from_email' => mb_substr($fromEmail, 0, 255),
                        'to_email' => $toList,
                        'cc_email' => $ccList,
                        'subject' => mb_substr($subject, 0, 255),
                        'body_html' => $bodyHtml,
                        'body_text' => $bodyText,
                        'is_seen' => $isSeen,
                        'has_attachment' => $hasAttachment,
                        'email_date' => $emailDate,
                    ]);

                    $synced++;
                } catch (\Exception $e) {
                    continue;
                }
            }

            $client->disconnect();

            // Update last sync time
            $account->last_synced_at = now();
            $account->save();

            return response()->json([
                'success' => true,
                'synced' => $synced,
                'message' => $synced > 0
                    ? "Berhasil sinkronisasi {$synced} email baru."
                    : 'Tidak ada email baru.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal sinkronisasi: ' . $e->getMessage(),
            ]);
        }
    }

    // ==========================================
    // INBOX: Read from local DB (instant)
    // ==========================================

    public function emailInbox(Request $request)
    {
        $accounts = EmailAccount::active()->orderBy('is_default', 'desc')->orderBy('name', 'asc')->get();

        if ($accounts->isEmpty()) {
            return view('back.pages.crm.email.inbox', [
                'accounts' => $accounts,
                'selectedAccount' => null,
                'emails' => collect(),
                'error' => null,
                'no_account' => true,
                'folder' => 'INBOX',
                'folders' => [],
            ]);
        }

        $selectedAccount = null;
        if ($request->has('account_id')) {
            $selectedAccount = EmailAccount::find($request->account_id);
        }
        if (!$selectedAccount) {
            $selectedAccount = $accounts->where('is_default', true)->first() ?? $accounts->first();
        }

        $folder = $request->get('folder', 'INBOX');

        // Read from local DB — instant!
        $emails = EmailMessage::forAccount($selectedAccount->id)
            ->forFolder($folder)
            ->orderByDesc('email_date')
            ->limit(50)
            ->get();

        // Get folder list from DB (saved during sync)
        $folderList = $selectedAccount->imap_folders ?? [];

        // Fallback: always include INBOX
        if (empty($folderList)) {
            $folderList = [['name' => 'INBOX', 'full_name' => 'INBOX', 'path' => 'INBOX']];
        }

        return view('back.pages.crm.email.inbox', [
            'accounts' => $accounts,
            'selectedAccount' => $selectedAccount,
            'emails' => $emails,
            'error' => null,
            'no_account' => false,
            'folder' => $folder,
            'folders' => $folderList,
        ]);
    }

    // ==========================================
    // SHOW EMAIL: Read from local DB
    // ==========================================

    public function emailShow(Request $request, $uid)
    {
        $accounts = EmailAccount::active()->orderBy('is_default', 'desc')->orderBy('name', 'asc')->get();

        $selectedAccount = null;
        if ($request->has('account_id')) {
            $selectedAccount = EmailAccount::find($request->account_id);
        }
        if (!$selectedAccount) {
            $selectedAccount = $accounts->where('is_default', true)->first() ?? $accounts->first();
        }

        if (!$selectedAccount) {
            Alert::error('Error', 'Tidak ada akun email.');
            return redirect()->route('back.crm.email.accounts');
        }

        $folder = $request->get('folder', 'INBOX');

        $email = EmailMessage::where('email_account_id', $selectedAccount->id)
            ->where('uid', $uid)
            ->where('folder', $folder)
            ->first();

        if (!$email) {
            Alert::error('Error', 'Email tidak ditemukan. Coba sync ulang.');
            return redirect()->route('back.crm.email.inbox', ['account_id' => $selectedAccount->id]);
        }

        // Mark as read
        if (!$email->is_seen) {
            $email->update(['is_seen' => true]);
        }

        $bodyHtml = $email->body_html;
        if (empty($bodyHtml) && !empty($email->body_text)) {
            $bodyHtml = nl2br(htmlspecialchars($email->body_text));
        }
        if (empty($bodyHtml)) {
            $bodyHtml = '<p class="text-muted"><em>Email ini tidak memiliki konten.</em></p>';
        }

        return view('back.pages.crm.email.show', [
            'uid' => $uid,
            'from_name' => $email->from_name,
            'from_email' => $email->from_email,
            'to_list' => $email->to_email ?? [],
            'cc_list' => $email->cc_email ?? [],
            'subject' => $email->subject,
            'date' => $email->email_date ? $email->email_date->format('d M Y H:i') : '',
            'body_html' => $bodyHtml,
            'attachments' => [],
            'selectedAccount' => $selectedAccount,
            'folder' => $folder,
        ]);
    }

    // ==========================================
    // COMPOSE & SEND
    // ==========================================

    public function emailCompose(Request $request)
    {
        $accounts = EmailAccount::active()->orderBy('is_default', 'desc')->orderBy('name', 'asc')->get();

        return view('back.pages.crm.email.compose', [
            'accounts' => $accounts,
            'selectedAccountId' => $request->get('account_id'),
            'to' => $request->get('to', ''),
            'subject' => $request->get('subject', ''),
            'replyBody' => $request->get('reply_body', ''),
        ]);
    }

    public function emailSend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_id' => 'required|exists:email_accounts,id',
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'cc' => 'nullable|string',
            'bcc' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->first());
            return redirect()->back()->withInput();
        }

        $account = EmailAccount::findOrFail($request->account_id);
        $smtpConfig = $account->getSmtpConfig();

        try {
            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp.host', $smtpConfig['host']);
            Config::set('mail.mailers.smtp.port', $smtpConfig['port']);
            Config::set('mail.mailers.smtp.encryption', $smtpConfig['encryption'] === 'none' ? null : $smtpConfig['encryption']);
            Config::set('mail.mailers.smtp.username', $smtpConfig['username']);
            Config::set('mail.mailers.smtp.password', $smtpConfig['password']);
            Config::set('mail.from.address', $account->email);
            Config::set('mail.from.name', $account->name);

            app('mail.manager')->purge('smtp');

            $to = $request->to;
            $subject = $request->subject;
            $body = $request->body;
            $ccEmails = $request->filled('cc') ? array_filter(array_map('trim', explode(',', $request->cc))) : [];
            $bccEmails = $request->filled('bcc') ? array_filter(array_map('trim', explode(',', $request->bcc))) : [];

            Mail::html($body, function ($message) use ($to, $subject, $ccEmails, $bccEmails, $account, $request) {
                $message->from($account->email, $account->name);
                $message->to($to);
                $message->subject($subject);

                if (!empty($ccEmails)) $message->cc($ccEmails);
                if (!empty($bccEmails)) $message->bcc($bccEmails);

                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $file) {
                        $message->attach($file->getRealPath(), [
                            'as' => $file->getClientOriginalName(),
                            'mime' => $file->getMimeType(),
                        ]);
                    }
                }
            });

            Alert::success('Berhasil', 'Email berhasil dikirim ke ' . $to);
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Gagal mengirim email: ' . $e->getMessage());
        }

        return redirect()->route('back.crm.email.inbox', ['account_id' => $account->id]);
    }

    // ==========================================
    // DELETE EMAIL
    // ==========================================

    public function emailDelete(Request $request)
    {
        $account = EmailAccount::findOrFail($request->account_id);
        $uid = $request->uid;
        $folder = $request->get('folder', 'INBOX');

        // Delete from local DB
        EmailMessage::where('email_account_id', $account->id)
            ->where('uid', $uid)
            ->where('folder', $folder)
            ->delete();

        // Try to delete from IMAP server too
        try {
            $client = $this->getImapClient($account);
            $client->connect();

            $imapFolder = $client->getFolder($folder);
            if ($imapFolder) {
                $message = $imapFolder->messages()->getMessageByUid((int) $uid);
                if ($message) {
                    $message->delete(true);
                }
            }

            $client->disconnect();
        } catch (\Exception $e) {
            // IMAP delete failed, but local is already deleted — OK
        }

        Alert::success('Berhasil', 'Email berhasil dihapus.');
        return redirect()->route('back.crm.email.inbox', ['account_id' => $account->id, 'folder' => $folder]);
    }

    // ==========================================
    // TELEGRAM BOT CRUD
    // ==========================================

    public function telegramBotIndex()
    {
        $bots = TelegramBot::orderBy('created_at', 'desc')->get();
        return view('back.pages.crm.telegram.bots', compact('bots'));
    }

    public function telegramBotStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->first());
            return redirect()->back()->withInput();
        }

        $username = null;
        try {
            $response = Http::get('https://api.telegram.org/bot' . $request->token . '/getMe');
            $data = $response->json();
            if (isset($data['ok']) && $data['ok'] && isset($data['result']['username'])) {
                $username = $data['result']['username'];
            }
        } catch (\Exception $e) {
            // Token mungkin tidak valid, tapi tetap simpan
        }

        TelegramBot::create([
            'name' => $request->name,
            'token' => $request->token,
            'username' => $username,
            'welcome_message' => $request->welcome_message,
            'created_by' => Auth::id(),
        ]);

        Alert::success('Berhasil', 'Bot Telegram berhasil ditambahkan.');
        return redirect()->route('back.crm.telegram.bots');
    }

    public function telegramBotUpdate(Request $request, $id)
    {
        $bot = TelegramBot::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->first());
            return redirect()->back()->withInput();
        }

        $bot->name = $request->name;
        $bot->welcome_message = $request->welcome_message;

        if ($request->filled('token')) {
            $bot->token = $request->token;
            // Verify new token
            try {
                $response = Http::get('https://api.telegram.org/bot' . $request->token . '/getMe');
                $data = $response->json();
                if (isset($data['ok']) && $data['ok'] && isset($data['result']['username'])) {
                    $bot->username = $data['result']['username'];
                }
            } catch (\Exception $e) {
                // Token verification failed
            }
        }

        $bot->save();

        Alert::success('Berhasil', 'Bot Telegram berhasil diperbarui.');
        return redirect()->route('back.crm.telegram.bots');
    }

    public function telegramBotDestroy($id)
    {
        $bot = TelegramBot::findOrFail($id);

        // Unset webhook if active
        if ($bot->webhook_active) {
            try {
                $bot->sendRequest('deleteWebhook');
            } catch (\Exception $e) {
                // Continue with deletion
            }
        }

        $bot->delete();

        Alert::success('Berhasil', 'Bot Telegram berhasil dihapus.');
        return redirect()->route('back.crm.telegram.bots');
    }

    public function telegramBotSetWebhook(Request $request)
    {
        $bot = TelegramBot::findOrFail($request->bot_id);
        $webhookUrl = url('/api/telegram/webhook/' . $bot->id);

        try {
            $result = $bot->sendRequest('setWebhook', [
                'url' => $webhookUrl,
            ]);

            if (isset($result['ok']) && $result['ok']) {
                $bot->update([
                    'webhook_url' => $webhookUrl,
                    'webhook_active' => true,
                ]);
                return response()->json(['success' => true, 'message' => 'Webhook berhasil diaktifkan.']);
            }

            return response()->json(['success' => false, 'message' => $result['description'] ?? 'Gagal mengatur webhook.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function telegramBotUnsetWebhook(Request $request)
    {
        $bot = TelegramBot::findOrFail($request->bot_id);

        try {
            $result = $bot->sendRequest('deleteWebhook');

            if (isset($result['ok']) && $result['ok']) {
                $bot->update([
                    'webhook_url' => null,
                    'webhook_active' => false,
                ]);
                return response()->json(['success' => true, 'message' => 'Webhook berhasil dinonaktifkan.']);
            }

            return response()->json(['success' => false, 'message' => $result['description'] ?? 'Gagal menghapus webhook.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    // ==========================================
    // TELEGRAM CHATS
    // ==========================================

    public function telegramChats(Request $request)
    {
        $bots = TelegramBot::active()->orderBy('name')->get();

        $selectedBot = null;
        if ($request->has('bot_id')) {
            $selectedBot = TelegramBot::find($request->bot_id);
        }
        if (!$selectedBot) {
            $selectedBot = $bots->first();
        }

        $chats = collect();
        if ($selectedBot) {
            $chats = TelegramChat::where('telegram_bot_id', $selectedBot->id)
                ->with(['messages' => fn($q) => $q->latest()->limit(1)])
                ->orderByDesc('last_message_at')
                ->get();
        }

        // Split-panel: load active chat when chat_id is provided
        $activeChat = null;
        if ($request->has('chat_id') && $request->chat_id) {
            $activeChat = TelegramChat::with(['bot', 'messages' => fn($q) => $q->latest()->limit(100)])
                ->find($request->chat_id);
        }

        return view('back.pages.crm.telegram.chats', compact('bots', 'selectedBot', 'chats', 'activeChat'));
    }

    public function telegramChatShow(Request $request, $id)
    {
        $chat = TelegramChat::findOrFail($id);
        // Redirect to split-panel view
        return redirect()->route('back.crm.telegram.chats', ['bot_id' => $chat->telegram_bot_id, 'chat_id' => $id]);
    }

    public function telegramSendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chat_id' => 'required',
            'text' => 'nullable|string',
            'bot_id' => 'required',
            'photo' => 'nullable|file|mimes:jpeg,png,gif,webp|max:5120',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->first());
            return redirect()->back();
        }

        // Must have text or photo
        if (!$request->text && !$request->hasFile('photo')) {
            Alert::error('Gagal', 'Kirim pesan teks atau gambar.');
            return redirect()->back();
        }

        $bot = TelegramBot::findOrFail($request->bot_id);
        $chat = TelegramChat::where('telegram_bot_id', $bot->id)
            ->where('chat_id', $request->chat_id)
            ->firstOrFail();

        try {
            if ($request->hasFile('photo')) {
                // Validate real image
                $file = $request->file('photo');
                if (!@getimagesize($file->getRealPath())) {
                    Alert::error('Gagal', 'File bukan gambar yang valid.');
                    return redirect()->back();
                }

                // Send photo via Telegram API
                $params = ['chat_id' => $request->chat_id];
                if ($request->text) {
                    $params['caption'] = $request->text;
                }

                $result = $bot->sendMultipart('sendPhoto', $params, $file, 'photo');

                if (isset($result['ok']) && $result['ok']) {
                    TelegramMessage::create([
                        'telegram_bot_id' => $bot->id,
                        'telegram_chat_id' => $chat->id,
                        'message_id' => $result['result']['message_id'] ?? null,
                        'direction' => 'out',
                        'text' => $request->text,
                        'type' => 'photo',
                        'file_id' => $result['result']['photo'][0]['file_id'] ?? null,
                        'sent_at' => now(),
                    ]);

                    Alert::success('Berhasil', 'Gambar berhasil dikirim.');
                } else {
                    Alert::error('Gagal', $result['description'] ?? 'Gagal mengirim gambar.');
                }
            } else {
                // Text only
                $result = $bot->sendRequest('sendMessage', [
                    'chat_id' => $request->chat_id,
                    'text' => $request->text,
                ]);

                if (isset($result['ok']) && $result['ok']) {
                    TelegramMessage::create([
                        'telegram_bot_id' => $bot->id,
                        'telegram_chat_id' => $chat->id,
                        'message_id' => $result['result']['message_id'] ?? null,
                        'direction' => 'out',
                        'text' => $request->text,
                        'type' => 'text',
                        'sent_at' => now(),
                    ]);

                    Alert::success('Berhasil', 'Pesan berhasil dikirim.');
                } else {
                    Alert::error('Gagal', $result['description'] ?? 'Gagal mengirim pesan.');
                }
            }
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Gagal mengirim: ' . $e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * Proxy Telegram file downloads — resolves file_id to actual file and streams it.
     */
    public function telegramFileProxy($botId, $fileId)
    {
        $bot = TelegramBot::findOrFail($botId);

        // Get file path from Telegram
        $result = $bot->sendRequest('getFile', ['file_id' => $fileId]);

        if (!isset($result['ok']) || !$result['ok'] || !isset($result['result']['file_path'])) {
            abort(404, 'File tidak ditemukan di Telegram.');
        }

        $filePath = $result['result']['file_path'];
        $fileUrl = 'https://api.telegram.org/file/bot' . $bot->token . '/' . $filePath;

        try {
            $response = Http::timeout(15)->get($fileUrl);

            if ($response->successful()) {
                $contentType = $response->header('Content-Type') ?: 'application/octet-stream';

                return response($response->body(), 200, [
                    'Content-Type' => $contentType,
                    'Cache-Control' => 'public, max-age=86400',
                ]);
            }
        } catch (\Exception $e) {
            // ignore
        }

        abort(404, 'Gagal mengambil file.');
    }

    // ==========================================
    // TELEGRAM WEBHOOK (PUBLIC)
    // ==========================================

    public function telegramWebhook(Request $request, $id)
    {
        $bot = TelegramBot::find($id);
        if (!$bot) {
            return response('OK', 200);
        }

        Log::info('Telegram Webhook [Bot: ' . $bot->name . ']', $request->all());

        $update = $request->all();

        // Get message from update
        $message = $update['message'] ?? $update['edited_message'] ?? null;
        if (!$message) {
            return response('OK', 200);
        }

        // Extract chat info
        $chatData = $message['chat'] ?? null;
        if (!$chatData) {
            return response('OK', 200);
        }

        $telegramChatId = $chatData['id'];
        $chatType = $chatData['type'] ?? 'private';

        // Create or update chat
        $chat = TelegramChat::updateOrCreate(
            [
                'telegram_bot_id' => $bot->id,
                'chat_id' => $telegramChatId,
            ],
            [
                'chat_type' => $chatType,
                'first_name' => $chatData['first_name'] ?? null,
                'last_name' => $chatData['last_name'] ?? null,
                'username' => $chatData['username'] ?? null,
                'title' => $chatData['title'] ?? null,
                'last_message_at' => now(),
            ]
        );

        // Determine message type and content
        $text = $message['text'] ?? $message['caption'] ?? null;
        $type = 'text';
        $fileId = null;
        $fileName = null;
        $mimeType = null;

        if (isset($message['photo'])) {
            $type = 'photo';
            // Take largest photo (last in array)
            $photos = $message['photo'];
            $fileId = end($photos)['file_id'] ?? null;
        } elseif (isset($message['document'])) {
            $type = 'document';
            $fileId = $message['document']['file_id'] ?? null;
            $fileName = $message['document']['file_name'] ?? null;
            $mimeType = $message['document']['mime_type'] ?? null;
        } elseif (isset($message['sticker'])) {
            $type = 'sticker';
            $fileId = $message['sticker']['file_id'] ?? null;
        } elseif (isset($message['video'])) {
            $type = 'video';
            $fileId = $message['video']['file_id'] ?? null;
            $mimeType = $message['video']['mime_type'] ?? null;
        } elseif (isset($message['voice'])) {
            $type = 'voice';
            $fileId = $message['voice']['file_id'] ?? null;
            $mimeType = $message['voice']['mime_type'] ?? null;
        } elseif (isset($message['location'])) {
            $type = 'location';
            $text = 'Lat: ' . ($message['location']['latitude'] ?? '') . ', Lng: ' . ($message['location']['longitude'] ?? '');
        } elseif (isset($message['contact'])) {
            $type = 'contact';
            $text = ($message['contact']['first_name'] ?? '') . ' ' . ($message['contact']['phone_number'] ?? '');
        }

        // Save incoming message
        TelegramMessage::create([
            'telegram_bot_id' => $bot->id,
            'telegram_chat_id' => $chat->id,
            'message_id' => $message['message_id'] ?? null,
            'direction' => 'in',
            'text' => $text,
            'type' => $type,
            'file_id' => $fileId,
            'file_name' => $fileName,
            'mime_type' => $mimeType,
            'reply_to_message_id' => $message['reply_to_message']['message_id'] ?? null,
            'sent_at' => isset($message['date']) ? Carbon::createFromTimestamp($message['date']) : now(),
        ]);

        // Broadcast notification to admins
        $senderName = trim(($chatData['first_name'] ?? '') . ' ' . ($chatData['last_name'] ?? '')) ?: ($chatData['username'] ?? 'Telegram User');
        $msgPreview = $text ?: '[' . ucfirst($type) . ']';
        event(new NewCrmMessage(
            'telegram',
            $senderName,
            $msgPreview,
            route('back.crm.telegram.chat.show', ['bot_id' => $bot->id, 'chat_id' => $chat->id])
        ));

        // Handle /start command
        if (isset($message['text']) && $message['text'] === '/start' && $bot->welcome_message) {
            try {
                $bot->sendRequest('sendMessage', [
                    'chat_id' => $telegramChatId,
                    'text' => $bot->welcome_message,
                ]);

                TelegramMessage::create([
                    'telegram_bot_id' => $bot->id,
                    'telegram_chat_id' => $chat->id,
                    'direction' => 'out',
                    'text' => $bot->welcome_message,
                    'type' => 'text',
                    'sent_at' => now(),
                ]);
            } catch (\Exception $e) {
                Log::error('Telegram: Gagal mengirim welcome message: ' . $e->getMessage());
            }
        }

        return response('OK', 200);
    }

    // ==========================================
    // EMAIL OVERVIEW
    // ==========================================

    public function emailOverview(Request $request)
    {
        $accounts = EmailAccount::active()->orderBy('is_default', 'desc')->orderBy('name', 'asc')->get();

        $selectedAccount = null;
        if ($request->has('account_id')) {
            $selectedAccount = EmailAccount::find($request->account_id);
        }
        if (!$selectedAccount) {
            $selectedAccount = $accounts->where('is_default', true)->first() ?? $accounts->first();
        }

        // Stats
        $stats = [
            'total_emails' => 0,
            'total_inbox' => 0,
            'total_sent' => 0,
            'total_spam' => 0,
            'total_trash' => 0,
            'total_unread' => 0,
            'emails_today' => 0,
            'emails_this_week' => 0,
            'emails_this_month' => 0,
        ];

        if ($selectedAccount) {
            $query = EmailMessage::where('email_account_id', $selectedAccount->id);
            $stats['total_emails'] = (clone $query)->count();
            $stats['total_inbox'] = (clone $query)->where('folder', 'INBOX')->count();
            $stats['total_sent'] = (clone $query)->where('folder', 'LIKE', '%sent%')->count();
            $stats['total_spam'] = (clone $query)->where(function ($q) {
                $q->where('folder', 'LIKE', '%spam%')->orWhere('folder', 'LIKE', '%junk%');
            })->count();
            $stats['total_trash'] = (clone $query)->where(function ($q) {
                $q->where('folder', 'LIKE', '%trash%')->orWhere('folder', 'LIKE', '%delete%');
            })->count();
            $stats['total_unread'] = (clone $query)->where('folder', 'INBOX')->where('is_seen', false)->count();
            $stats['emails_today'] = (clone $query)->whereDate('email_date', Carbon::today())->count();
            $stats['emails_this_week'] = (clone $query)->where('email_date', '>=', Carbon::now()->startOfWeek())->count();
            $stats['emails_this_month'] = (clone $query)->where('email_date', '>=', Carbon::now()->startOfMonth())->count();
        }

        // Daily email count for last 30 days
        $chartData = ['labels' => [], 'data' => []];
        if ($selectedAccount) {
            $dailyCounts = EmailMessage::where('email_account_id', $selectedAccount->id)
                ->where('email_date', '>=', Carbon::now()->subDays(30))
                ->selectRaw('DATE(email_date) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get()
                ->keyBy('date');

            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $chartData['labels'][] = Carbon::parse($date)->format('d M');
                $chartData['data'][] = $dailyCounts->has($date) ? $dailyCounts[$date]->count : 0;
            }
        }

        $totalGroups = EmailGroup::count();
        $totalContacts = EmailContact::count();
        $totalCampaigns = EmailCampaign::count();
        $recentCampaigns = EmailCampaign::with(['emailAccount', 'group'])->orderBy('created_at', 'desc')->limit(5)->get();

        return view('back.pages.crm.email.overview', compact(
            'accounts', 'selectedAccount', 'stats', 'chartData',
            'totalGroups', 'totalContacts', 'totalCampaigns', 'recentCampaigns'
        ));
    }

    // ==========================================
    // EMAIL GROUPS
    // ==========================================

    public function emailGroupIndex()
    {
        $groups = EmailGroup::withCount('contacts')->orderBy('name', 'asc')->get();
        return view('back.pages.crm.email.groups', compact('groups'));
    }

    public function emailGroupStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->first());
            return redirect()->back()->withInput();
        }

        EmailGroup::create([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color ?? '#3699FF',
            'created_by' => Auth::id(),
        ]);

        Alert::success('Berhasil', 'Grup kontak berhasil ditambahkan.');
        return redirect()->back();
    }

    public function emailGroupUpdate(Request $request, $id)
    {
        $group = EmailGroup::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->first());
            return redirect()->back()->withInput();
        }

        $group->update([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color ?? $group->color,
        ]);

        Alert::success('Berhasil', 'Grup kontak berhasil diperbarui.');
        return redirect()->back();
    }

    public function emailGroupDestroy($id)
    {
        $group = EmailGroup::findOrFail($id);
        $group->delete();

        Alert::success('Berhasil', 'Grup kontak berhasil dihapus.');
        return redirect()->back();
    }

    // ==========================================
    // EMAIL CONTACTS
    // ==========================================

    public function emailContactIndex(Request $request, $groupId)
    {
        $group = EmailGroup::findOrFail($groupId);
        $contacts = EmailContact::where('email_group_id', $groupId)->orderBy('name', 'asc')->paginate(20);
        return view('back.pages.crm.email.contacts', compact('group', 'contacts'));
    }

    public function emailContactStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_group_id' => 'required|exists:email_groups,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->first());
            return redirect()->back()->withInput();
        }

        EmailContact::create([
            'email_group_id' => $request->email_group_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company,
        ]);

        Alert::success('Berhasil', 'Kontak berhasil ditambahkan.');
        return redirect()->back();
    }

    public function emailContactDestroy($id)
    {
        $contact = EmailContact::findOrFail($id);
        $contact->delete();

        Alert::success('Berhasil', 'Kontak berhasil dihapus.');
        return redirect()->back();
    }

    public function emailContactImport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'required|exists:email_groups,id',
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->first());
            return redirect()->back();
        }

        $file = $request->file('csv_file');
        $content = file_get_contents($file->getRealPath());
        $lines = array_filter(explode("\n", $content));
        $count = 0;

        foreach ($lines as $index => $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $parts = str_getcsv($line);
            if (count($parts) < 2) continue;

            $name = trim($parts[0]);
            $email = trim($parts[1]);

            // Skip header row
            if ($index === 0 && (strtolower($name) === 'name' || strtolower($name) === 'nama')) {
                continue;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) continue;

            EmailContact::create([
                'email_group_id' => $request->group_id,
                'name' => $name,
                'email' => $email,
            ]);
            $count++;
        }

        Alert::success('Berhasil', "Berhasil mengimpor {$count} kontak.");
        return redirect()->back();
    }

    // ==========================================
    // EMAIL CAMPAIGNS
    // ==========================================

    public function emailCampaignIndex()
    {
        $campaigns = EmailCampaign::with(['emailAccount', 'group'])->orderBy('created_at', 'desc')->get();
        return view('back.pages.crm.email.campaigns', compact('campaigns'));
    }

    public function emailCampaignCreate()
    {
        $accounts = EmailAccount::active()->orderBy('name', 'asc')->get();
        $groups = EmailGroup::withCount('contacts')->orderBy('name', 'asc')->get();
        return view('back.pages.crm.email.campaign-compose', compact('accounts', 'groups'));
    }

    public function emailCampaignStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email_account_id' => 'required|exists:email_accounts,id',
            'email_group_id' => 'required|exists:email_groups,id',
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->first());
            return redirect()->back()->withInput();
        }

        EmailCampaign::create([
            'name' => $request->name,
            'email_account_id' => $request->email_account_id,
            'email_group_id' => $request->email_group_id,
            'subject' => $request->subject,
            'body_html' => $request->body_html,
            'status' => 'draft',
            'created_by' => Auth::id(),
        ]);

        Alert::success('Berhasil', 'Campaign berhasil dibuat sebagai draft.');
        return redirect()->route('back.crm.email.campaigns');
    }

    public function emailCampaignSend(Request $request)
    {
        set_time_limit(0);

        $campaign = EmailCampaign::with(['emailAccount', 'group'])->findOrFail($request->campaign_id);

        if ($campaign->status !== 'draft') {
            return response()->json(['success' => false, 'message' => 'Campaign ini sudah dikirim atau sedang dikirim.']);
        }

        $contacts = EmailContact::where('email_group_id', $campaign->email_group_id)->subscribed()->get();

        if ($contacts->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Tidak ada kontak yang aktif di grup ini.']);
        }

        $campaign->update([
            'total_recipients' => $contacts->count(),
            'status' => 'sending',
        ]);

        $account = $campaign->emailAccount;
        $smtpConfig = $account->getSmtpConfig();
        $sentCount = 0;
        $failedCount = 0;

        foreach ($contacts as $contact) {
            try {
                Config::set('mail.default', 'smtp');
                Config::set('mail.mailers.smtp.host', $smtpConfig['host']);
                Config::set('mail.mailers.smtp.port', $smtpConfig['port']);
                Config::set('mail.mailers.smtp.encryption', $smtpConfig['encryption'] === 'none' ? null : $smtpConfig['encryption']);
                Config::set('mail.mailers.smtp.username', $smtpConfig['username']);
                Config::set('mail.mailers.smtp.password', $smtpConfig['password']);
                Config::set('mail.from.address', $account->email);
                Config::set('mail.from.name', $account->name);

                app('mail.manager')->purge('smtp');

                $body = $campaign->body_html;
                $subject = $campaign->subject;

                Mail::html($body, function ($message) use ($contact, $subject, $account) {
                    $message->from($account->email, $account->name);
                    $message->to($contact->email, $contact->name);
                    $message->subject($subject);
                });

                EmailCampaignLog::create([
                    'email_campaign_id' => $campaign->id,
                    'email_contact_id' => $contact->id,
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);

                $sentCount++;
            } catch (\Exception $e) {
                EmailCampaignLog::create([
                    'email_campaign_id' => $campaign->id,
                    'email_contact_id' => $contact->id,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);

                $failedCount++;
            }
        }

        $campaign->update([
            'sent_count' => $sentCount,
            'failed_count' => $failedCount,
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Campaign berhasil dikirim. Terkirim: {$sentCount}, Gagal: {$failedCount}",
            'sent_count' => $sentCount,
            'failed_count' => $failedCount,
        ]);
    }

    public function emailCampaignDestroy($id)
    {
        $campaign = EmailCampaign::findOrFail($id);
        $campaign->delete();

        Alert::success('Berhasil', 'Campaign berhasil dihapus.');
        return redirect()->back();
    }

    // ==========================================
    // WEBCHAT MANAGEMENT
    // ==========================================

    public function webchatIndex(Request $request)
    {
        $widgets = \App\Models\WebchatWidget::all();
        $selectedWidget = null;

        $query = \App\Models\WebchatConversation::with(['widget', 'messages' => fn($q) => $q->latest()->limit(1)])
            ->withCount(['messages as unread_count' => function ($q) {
                $q->where('sender', 'visitor')->where('is_read', false);
            }]);

        if ($request->has('widget_id') && $request->widget_id) {
            $selectedWidget = \App\Models\WebchatWidget::find($request->widget_id);
            if ($selectedWidget) {
                $query->where('webchat_widget_id', $selectedWidget->id);
            }
        }

        $conversations = $query->orderBy('last_message_at', 'desc')->paginate(50);

        // Split-panel: load active conversation when chat_id is provided
        $activeConversation = null;
        if ($request->has('chat_id') && $request->chat_id) {
            $activeConversation = \App\Models\WebchatConversation::with(['messagesAsc', 'widget'])->find($request->chat_id);
            if ($activeConversation) {
                // Mark visitor messages as read
                $activeConversation->messages()
                    ->where('sender', 'visitor')
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
            }
        }

        return view('back.pages.crm.webchat.index', compact('conversations', 'widgets', 'selectedWidget', 'activeConversation'));
    }

    public function webchatShow($id)
    {
        // Redirect to split-panel view
        return redirect()->route('back.crm.webchat.index', ['chat_id' => $id]);
    }

    public function webchatReply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $conversation = \App\Models\WebchatConversation::findOrFail($id);

        \App\Models\WebchatMessage::create([
            'webchat_conversation_id' => $conversation->id,
            'sender' => 'admin',
            'admin_user_id' => Auth::id(),
            'message' => $request->message,
            'is_read' => false,
        ]);

        $conversation->update([
            'last_message_at' => now(),
            'status' => 'active',
        ]);

        Alert::success('Berhasil', 'Pesan berhasil dikirim.');
        return redirect()->back();
    }

    public function webchatClose($id)
    {
        $conversation = \App\Models\WebchatConversation::findOrFail($id);
        $conversation->update(['status' => 'closed']);

        Alert::success('Berhasil', 'Percakapan telah ditutup.');
        return redirect()->route('back.crm.webchat.index');
    }

    public function webchatDestroy($id)
    {
        $conversation = \App\Models\WebchatConversation::findOrFail($id);
        $conversation->delete();

        Alert::success('Berhasil', 'Percakapan berhasil dihapus.');
        return redirect()->route('back.crm.webchat.index');
    }

    public function webchatFetchNew(Request $request, $id)
    {
        $conversation = \App\Models\WebchatConversation::findOrFail($id);

        $lastId = $request->input('last_id', 0);
        $messages = $conversation->messagesAsc()
            ->where('id', '>', $lastId)
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'sender' => $msg->sender,
                    'message' => $msg->message,
                    'image' => $msg->image ? asset('storage/' . $msg->image) : null,
                    'time' => $msg->created_at->format('H:i'),
                    'date' => $msg->created_at->format('d M Y'),
                    'admin_name' => $msg->adminUser ? $msg->adminUser->name : null,
                ];
            });

        // Mark visitor messages as read
        $conversation->messages()
            ->where('sender', 'visitor')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    public function webchatReplyAjax(Request $request, $id)
    {
        $request->validate([
            'message' => 'nullable|string|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,gif,webp|max:5120',
        ]);

        $conversation = \App\Models\WebchatConversation::findOrFail($id);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Verify real MIME type from content
            $realMime = $file->getMimeType();
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($realMime, $allowed)) {
                return response()->json(['success' => false, 'error' => 'Invalid image type'], 422);
            }

            // Verify it's a real image
            if (@getimagesize($file->getPathname()) === false) {
                return response()->json(['success' => false, 'error' => 'Corrupted image file'], 422);
            }

            // Random filename
            $ext = $file->guessExtension() ?: 'jpg';
            $filename = bin2hex(random_bytes(16)) . '.' . $ext;
            $imagePath = $file->storeAs('webchat', $filename, 'public');
        }

        $message = \App\Models\WebchatMessage::create([
            'webchat_conversation_id' => $conversation->id,
            'sender' => 'admin',
            'admin_user_id' => Auth::id(),
            'message' => $request->input('message', ''),
            'image' => $imagePath,
            'is_read' => false,
        ]);

        $conversation->update([
            'last_message_at' => now(),
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender' => $message->sender,
                'message' => $message->message,
                'image' => $message->image ? asset('storage/' . $message->image) : null,
                'time' => $message->created_at->format('H:i'),
                'date' => $message->created_at->format('d M Y'),
                'admin_name' => Auth::user()->name,
            ],
        ]);
    }

    // ==========================================
    // WEBCHAT WIDGET CRUD
    // ==========================================

    public function webchatWidgetIndex()
    {
        $widgets = \App\Models\WebchatWidget::withCount('conversations')->orderBy('created_at', 'desc')->get();
        return view('back.pages.crm.webchat.widgets', compact('widgets'));
    }

    public function webchatWidgetStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'header_title' => 'required|string|max:255',
            'header_subtitle' => 'nullable|string|max:255',
            'greeting_message' => 'nullable|string|max:1000',
            'primary_color' => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
            'allowed_domains' => 'nullable|string|max:1000',
        ]);

        \App\Models\WebchatWidget::create($request->only([
            'name', 'header_title', 'header_subtitle', 'greeting_message',
            'primary_color', 'secondary_color', 'allowed_domains',
        ]));

        Alert::success('Berhasil', 'Widget webchat berhasil dibuat.');
        return redirect()->back();
    }

    public function webchatWidgetUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'header_title' => 'required|string|max:255',
            'header_subtitle' => 'nullable|string|max:255',
            'greeting_message' => 'nullable|string|max:1000',
            'primary_color' => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
            'allowed_domains' => 'nullable|string|max:1000',
            'is_active' => 'required|boolean',
        ]);

        $widget = \App\Models\WebchatWidget::findOrFail($id);
        $widget->update($request->only([
            'name', 'header_title', 'header_subtitle', 'greeting_message',
            'primary_color', 'secondary_color', 'allowed_domains', 'is_active',
        ]));

        Alert::success('Berhasil', 'Widget webchat berhasil diperbarui.');
        return redirect()->back();
    }

    public function webchatWidgetDestroy($id)
    {
        $widget = \App\Models\WebchatWidget::findOrFail($id);
        $widget->delete();

        Alert::success('Berhasil', 'Widget webchat berhasil dihapus.');
        return redirect()->back();
    }
}

