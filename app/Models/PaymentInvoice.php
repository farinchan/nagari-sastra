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
    ];

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'payment_invoice_id');
    }

}
