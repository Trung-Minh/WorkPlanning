<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class MucCongViec extends Model
{
    use HasFactory;

    protected $table = 'MUC_CONG_VIEC';

    protected $keyType = 'string'; //đinh dạng chuỗi cho laravel hiểu ID_CV

    public $incrementing = false;

    protected $primaryKey = 'ID_MUC';

    public $timestamps = false;

    protected $fillable = [
        'ID_MUC',
        'ID_CV',
        'TEN_MUC',
        'NOI_DUNG_CHI_TIET',
        'THOI_HAN_HOAN_THANH',
        'TRANG_THAI',
        'DO_UU_TIEN_MUC',
    ];

    protected $casts = [
        'THOI_HAN_HOAN_THANH' => 'datetime',
    ];

    // 1 mục công việc thuộc về 1 công việc
    public function congViec()
    {
        return $this->belongsTo(CongViec::class, 'ID_CV', 'ID_CV');
    }
}
