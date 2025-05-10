<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'tb_reports';

    protected $fillable = [
        'reporter_id', 'reported_user_id', 'reason', 'status',
        'created_by', 'updated_by',
    ];

    // Quan hệ đến user
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reported()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }
}
