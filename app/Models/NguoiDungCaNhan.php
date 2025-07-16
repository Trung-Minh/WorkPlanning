<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class NguoiDungCaNhan extends Authenticatable
{
    use Notifiable;

    protected $table = 'nguoi_dung_ca_nhan';
    protected $primaryKey = 'ID_USER';
    public $incrementing = false;
    protected $keyType = 'string';
  
    public $timestamps = false;

    protected $fillable = [
        'ID_USER',
        'ho_ten',
        'email',
        'mat_khau',
        'ngay_sinh',
        'gioi_tinh',
        'AVATAR',
        'ANH_BIA',
    ];
  
    protected $hidden = ['mat_khau'];

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
