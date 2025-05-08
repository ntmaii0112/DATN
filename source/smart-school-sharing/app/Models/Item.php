<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'tb_items';
    protected $fillable = [
        'user_id', 'name', 'description', 'category_id',
        'item_condition', 'status', 'created_by', 'updated_by','deposit_amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(ItemImage::class, 'item_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'item_id');
    }

    protected static function booted()
    {
        static::addGlobalScope('not_deleted', function ($query) {
            $query->where('del_flag', false);
        });
    }
    public function rejections()
    {
        return $this->hasMany(ItemRejection::class, 'item_id');
    }
}
