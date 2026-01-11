<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ThongBao extends Model
{
    protected $table = 'THONG_BAO';
    protected $primaryKey = 'ID_TB';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected static function booted() {
        static::creating(function ($model) {
            $maxId = DB::table('THONG_BAO')
                ->select(DB::raw('MAX(CAST(SUBSTRING(ID_TB, 3) AS UNSIGNED)) as max_val'))
                ->value('max_val') ?? 0;
            $model->ID_TB = 'TB' . str_pad($maxId + 1, 6, '0', STR_PAD_LEFT);
        });
    }
}
