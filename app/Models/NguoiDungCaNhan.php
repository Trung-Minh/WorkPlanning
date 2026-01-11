<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class NguoiDungCaNhan extends Authenticatable
{
    use Notifiable;

    protected $table = 'NGUOI_DUNG_CA_NHAN';

    protected $primaryKey = 'ID_USER';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'HO_TEN',
        'EMAIL',
        'MAT_KHAU',
        'NGAY_SINH',
        'GIOI_TINH',
        'AVATAR',
        'ANH_BIA',
    ];

    protected $hidden = ['mat_khau'];


    protected static function booted()
    {
        static::creating(function ($model) {
            // Lấy số lớn nhất hiện tại (ví dụ: lấy số 0001 từ NDCN0001)
            $maxId = static::max(DB::raw('CAST(SUBSTRING(ID_USER, 5) AS UNSIGNED)')) ?? 0;

            // Sinh ID mới: NDCN + số thứ tự tăng dần, bù số 0 ở trước
            $model->ID_USER = 'NDCN' . str_pad($maxId + 1, 4, '0', STR_PAD_LEFT);
        });
    }

    public function getAuthIdentifierName()
    {
        return 'ID_USER';
    }

    public function getAuthIdentifier()
    {
        return $this->ID_USER;
    }

    public function getAuthPassword()
    {
        return $this->attributes['MAT_KHAU'];
    }

    public function keHoachs()
    {
        return $this->hasMany(KeHoach::class, 'NGUOI_TAO', 'ID_USER');
    }
}
