<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class NguoiDungCaNhan extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'nguoi_dung_ca_nhan';
    protected $authPasswordName = 'mat_khau';
    protected $hidden = ['mat_khau'];


    public $timestamps = false;
    protected $fillable = [
        'ho_ten',
        'email',
        'mat_khau',
        'ngay_sinh',
        'gioi_tinh'
    ];

    public function getMatKhauAttribute()
    {
        return $this->attributes['MAT_KHAU'] ?? null;
    }

    public function keHoachs()
    {
        return $this->hasMany(KeHoach::class, 'NGUOI_TAO', 'ID_USER');
    }
}
