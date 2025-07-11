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

    protected $primaryKey = 'ID_USER';
    protected $authPasswordName = 'MAT_KHAU';
    protected $hidden = ['MAT_KHAU'];

    // Thông báo khoá chính không auto-increment
    public $incrementing = false;

    // Khóa chính là chuỗi, không phải integer
    protected $keyType = 'string';


    public $timestamps = false;

    protected $fillable = [
        'ho_ten',
        'email',
        'MAT_KHAU',
        'ngay_sinh',
        'gioi_tinh',
        'AVATAR',
        'ANH_BIA',
    ];

    public function getMatKhauAttribute()
    {
        return $this->attributes['MAT_KHAU'] ?? null;
    }


    public function setMatKhauAttribute($value)
    {
        $this->attributes['MAT_KHAU'] = Hash::make($value);
    }

    public function getAuthPassword()
    {
        return $this->MAT_KHAU;
    }

    public function keHoachs()
    {
        return $this->hasMany(KeHoach::class, 'NGUOI_TAO', 'ID_USER');

    }
}
