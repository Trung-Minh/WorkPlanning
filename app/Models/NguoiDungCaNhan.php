<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class NguoiDungCaNhan extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'nguoi_dung_ca_nhan';
    protected $primaryKey = 'ID_USER';
    protected $authPasswordName = 'mat_khau';
    protected $hidden = ['mat_khau'];

    // Khicomment 2 dòng dưới thì lỗi session id_user = 0, còn không comment thì không thể đăng nhập
    // public $incrementing = false;
    // protected $keyType = 'string';

    public $timestamps = false;
    protected $fillable = [
        'ho_ten',
        'email',
        'mat_khau',
        'ngay_sinh',
        'gioi_tinh',
        'AVATAR',
        'ANH_BIA',
    ];

    public function getMatKhauAttribute()
    {
        return $this->attributes['MAT_KHAU'] ?? null;
    }

    public function getAuthIdentifier()
    {
        return $this->ID_USER;
    }

    public function getAuthIdentifierName()
    {
        return 'ID_USER';
    }


    public function getAuthPassword()
    {
        return $this->mat_khau;
    }

    public function keHoachs()
    {
        return $this->hasMany(KeHoach::class, 'NGUOI_TAO', 'ID_USER');

    }
}
