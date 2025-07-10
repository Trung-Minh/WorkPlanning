<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $table = 'CAU_HINH_THONG_BAO';

    protected $primaryKey = 'ID_CAUHINH';

    public $timestamps = false; // nếu bảng không có created_at, updated_at

    public function user()
    {
        return $this->belongsTo(User::class, 'ID_USER', 'ID_USER');
    }
}
