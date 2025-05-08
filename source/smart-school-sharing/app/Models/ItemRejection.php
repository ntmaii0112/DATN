<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemRejection extends Model
{
    use HasFactory;
    protected $table = 'tb_item_rejections';

    protected $fillable = [
        'item_id',
        'rejected_by',
        'reason',
    ];

    // Quan hệ: rejection thuộc về một item
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    // Quan hệ: rejection do một user thực hiện
    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}
