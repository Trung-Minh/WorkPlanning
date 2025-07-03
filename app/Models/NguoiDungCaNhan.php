<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class NguoiDungCaNhan extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'nguoi_dung_ca_nhan';
    protected $primaryKey = 'id_user'; // KHÓA CHÍNH PHẢI CÓ GIÁ TRỊ
    public $timestamps = false;

    protected $fillable = [
        'ho_ten',
        'email',
        'MAT_KHAU',
        'ngay_sinh',
        'gioi_tinh'
    ];

    protected $hidden = ['MAT_KHAU'];

    public function getMatKhauAttribute()
    {
        return $this->attributes['MAT_KHAU'] ?? null;
    }

    public function setMatKhauAttribute($value)
    {
        $this->attributes['MAT_KHAU'] = Hash::make($value);
    }
}
