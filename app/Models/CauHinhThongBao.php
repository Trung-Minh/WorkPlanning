<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CauHinhThongBao extends Model
{
    use HasFactory;

    protected $table = 'cau_hinh_thong_bao';

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
}
