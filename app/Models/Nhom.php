<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Nhom extends Model
{
    protected $table = 'nhom_lam_viec'; // tên bảng chính xác trong DB

    public   $primaryKey = 'ID_NHOM';
    protected $keyType = 'string';

    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'ID_NHOM_TRUONG',
        'NGAY_TAO',
        'MO_TA_NHOM',
        'TEN_NHOM',
        'AVATAR_NHOM',
    ];

    public function truongNhom()
    {
        return $this->belongsTo(NguoiDungCaNhan::class, 'ID_NHOM_TRUONG', 'ID_USER');
    }

    public function thanhVien()
    {
        return $this->belongsToMany(NguoiDungCaNhan::class, 'NHOM_THANH_VIEN', 'ID_NHOM', 'ID_USER');
    }

    protected static function booted() {
        static::creating(function ($model) {
            $maxId = DB::table('NHOM_LAM_VIEC')
                ->select(DB::raw('MAX(CAST(SUBSTRING(ID_NHOM, 4) AS UNSIGNED)) as max_val'))
                ->value('max_val') ?? 0;
            $model->ID_NHOM = 'NLV' . str_pad($maxId + 1, 5, '0', STR_PAD_LEFT);
        });
    }

}
