<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\EmailContact;
use App\Models\EmailGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        // Rate limit: max 5 per minute per IP
        $rateLimitKey = 'newsletter:' . $request->ip();
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            return response()->json([
                'success' => false,
                'message' => 'Terlalu banyak percobaan. Silakan coba lagi nanti.',
            ], 429);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('email'),
            ], 422);
        }

        // Honeypot check
        if ($request->filled('website_url')) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil subscribe! Terima kasih.',
            ]);
        }

        // Get or create the "Newsletter" group
        $group = EmailGroup::firstOrCreate(
            ['name' => 'Newsletter'],
            [
                'description' => 'Subscriber dari form newsletter website',
                'color' => '#2a80b9',
            ]
        );

        // Check if already subscribed
        $existing = EmailContact::where('email_group_id', $group->id)
            ->where('email', $request->email)
            ->first();

        if ($existing) {
            if ($existing->is_subscribed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email ini sudah terdaftar sebagai subscriber.',
                ], 409);
            }

            // Re-subscribe
            $existing->update(['is_subscribed' => true]);

            RateLimiter::hit($rateLimitKey, 60);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil subscribe kembali! Terima kasih.',
            ]);
        }

        // Create new contact
        EmailContact::create([
            'email_group_id' => $group->id,
            'name' => strip_tags(explode('@', $request->email)[0]),
            'email' => $request->email,
            'is_subscribed' => true,
        ]);

        RateLimiter::hit($rateLimitKey, 60);

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih telah subscribe! Anda akan menerima info terbaru dari kami.',
        ]);
    }
}
