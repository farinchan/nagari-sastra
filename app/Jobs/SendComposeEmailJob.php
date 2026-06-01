<?php

namespace App\Jobs;

use App\Models\EmailAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendComposeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;

    protected int $accountId;
    protected string $to;
    protected string $subject;
    protected string $body;
    protected array $ccEmails;
    protected array $bccEmails;
    protected array $attachmentPaths; // [{path, name, mime}]

    public function __construct(
        int $accountId,
        string $to,
        string $subject,
        string $body,
        array $ccEmails = [],
        array $bccEmails = [],
        array $attachmentPaths = []
    ) {
        $this->accountId = $accountId;
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
        $this->ccEmails = $ccEmails;
        $this->bccEmails = $bccEmails;
        $this->attachmentPaths = $attachmentPaths;
    }

    public function handle(): void
    {
        $account = EmailAccount::findOrFail($this->accountId);
        $smtpConfig = $account->getSmtpConfig();

        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.host', $smtpConfig['host']);
        Config::set('mail.mailers.smtp.port', $smtpConfig['port']);
        Config::set('mail.mailers.smtp.encryption', $smtpConfig['encryption'] === 'none' ? null : $smtpConfig['encryption']);
        Config::set('mail.mailers.smtp.username', $smtpConfig['username']);
        Config::set('mail.mailers.smtp.password', $smtpConfig['password']);
        Config::set('mail.from.address', $account->email);
        Config::set('mail.from.name', $account->name);

        app('mail.manager')->purge('smtp');

        $to = $this->to;
        $subject = $this->subject;
        $body = $this->body;
        $ccEmails = $this->ccEmails;
        $bccEmails = $this->bccEmails;
        $attachmentPaths = $this->attachmentPaths;

        Mail::html($body, function ($message) use ($to, $subject, $ccEmails, $bccEmails, $account, $attachmentPaths) {
            $message->from($account->email, $account->name);
            $message->to($to);
            $message->subject($subject);

            if (!empty($ccEmails)) $message->cc($ccEmails);
            if (!empty($bccEmails)) $message->bcc($bccEmails);

            foreach ($attachmentPaths as $att) {
                $message->attach($att['path'], [
                    'as' => $att['name'],
                    'mime' => $att['mime'],
                ]);
            }
        });

        // Clean up temporary attachment files
        foreach ($attachmentPaths as $att) {
            if (file_exists($att['path'])) {
                @unlink($att['path']);
            }
        }

        Log::info("Email sent via queue to {$to} from {$account->email}");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("SendComposeEmailJob failed: " . $exception->getMessage());

        // Clean up temporary attachment files on failure too
        foreach ($this->attachmentPaths as $att) {
            if (file_exists($att['path'])) {
                @unlink($att['path']);
            }
        }
    }
}
