<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PaymentInvoice extends Model
{
    use LogsActivity;
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'payment_invoice_id');
    }

}
