<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nhom extends Model
{
    protected $table = 'nhom_lam_viec'; // tên bảng chính xác trong DB

     $primaryKey = 'ID_NHOM';
    protected $keyType = 'string';

    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'ID_NHOM_TRUONG',
        'NGAY_TAO',
        'MO_TA_NHOM',
        'TEN_NHOM',
    ];
}
