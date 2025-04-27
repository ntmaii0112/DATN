<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'tb_transactions';

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'giver_id',
        'receiver_id',
        'item_id',
        'status',
        'created_by',
        'updated_by',
        'borrower_name',
        'contact_info',
        'start_date',
        'end_date',
        'purpose',
        'message',
        'request_status'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function giver()
    {
        return $this->belongsTo(User::class, 'giver_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function getFirstImageUrlAttribute()
    {
        return $this->item->images->first()->image_url ?? null;
    }

    /**
     * Check if transaction can be cancelled
     */
    public function canBeCancelled()
    {
        return $this->status !== 'cancelled' && $this->status !== 'completed';
    }
}
