<?php

namespace App\Jobs;

use App\Models\EmailAccount;
use App\Models\EmailCampaign;
use App\Models\EmailCampaignLog;
use App\Models\EmailContact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendCampaignEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;
    public int $timeout = 600; // 10 minutes for large campaigns

    protected int $campaignId;

    public function __construct(int $campaignId)
    {
        $this->campaignId = $campaignId;
        $this->onQueue('emails');
    }

    public function handle(): void
    {
        $campaign = EmailCampaign::with(['emailAccount', 'group'])->findOrFail($this->campaignId);

        if ($campaign->status !== 'sending') {
            Log::warning("Campaign #{$this->campaignId} is not in sending state, skipping.");
            return;
        }

        $contacts = EmailContact::where('email_group_id', $campaign->email_group_id)->subscribed()->get();

        if ($contacts->isEmpty()) {
            $campaign->update(['status' => 'failed']);
            Log::warning("Campaign #{$this->campaignId} has no active contacts.");
            return;
        }

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

        Log::info("Campaign #{$this->campaignId} completed. Sent: {$sentCount}, Failed: {$failedCount}");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("SendCampaignEmailJob failed for campaign #{$this->campaignId}: " . $exception->getMessage());

        $campaign = EmailCampaign::find($this->campaignId);
        if ($campaign && $campaign->status === 'sending') {
            $campaign->update(['status' => 'failed']);
        }
    }
}
