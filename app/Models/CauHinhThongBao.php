<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CauHinhThongBao extends Model
{
    use HasFactory;

    protected $table = 'cau_hinh_thong_bao';

    protected $keyType = 'string'; //đinh dạng chuỗi cho laravel hiểu ID_cauhinh

    public $incrementing = false;

    protected $primaryKey = 'ID_CAUHINH';

    public $timestamps = false;

    protected $fillable = [
        'THOI_GIAN_TRUOC_HAN',
        'NOI_DUNG_TB',
        'ID_USER',
        'ID_MUC',
        'THOI_DIEM_THONG_BAO',
    ];

    public function mucCongViec()
    {
        return $this->belongsTo(MucCongViec::class, 'ID_MUC', 'ID_MUC');
    }

    protected static function booted() {
        static::creating(function ($model) {
            $maxId = DB::table('CAU_HINH_THONG_BAO')
                ->select(DB::raw('MAX(CAST(SUBSTRING(ID_CAUHINH, 5) AS UNSIGNED)) as max_val'))
                ->value('max_val') ?? 0;
            $model->ID_CAUHINH = 'CHTB' . str_pad($maxId + 1, 4, '0', STR_PAD_LEFT);
        });
    }
}
