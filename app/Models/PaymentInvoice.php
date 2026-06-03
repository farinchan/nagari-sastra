<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentInvoice extends Model
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'items' => 'array',
        'midtrans_response' => 'array',
        'payment_due_date' => 'date',
        'confirmed_at' => 'datetime',
        'midtrans_paid_at' => 'datetime',
        'is_paid' => 'boolean',
        'is_custom' => 'boolean',
    ];

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'payment_invoice_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

}
