<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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



}
