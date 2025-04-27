<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model
{
    protected $table = 'tb_item_images';

    protected $fillable = [
        'item_id',
        'image_url',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    public $timestamps = false; // vì bạn đang sử dụng thủ công created_at, updated_at

    // Quan hệ ngược về Item
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
