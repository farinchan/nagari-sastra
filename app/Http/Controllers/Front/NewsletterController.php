<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\EmailContact;
use App\Models\EmailGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
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

            return response()->json([
                'success' => true,
                'message' => 'Berhasil subscribe kembali! Terima kasih.',
            ]);
        }

        // Create new contact
        EmailContact::create([
            'email_group_id' => $group->id,
            'name' => explode('@', $request->email)[0],
            'email' => $request->email,
            'is_subscribed' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih telah subscribe! Anda akan menerima info terbaru dari kami.',
        ]);
    }
}
