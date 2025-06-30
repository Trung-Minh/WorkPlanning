<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class NguoiDungCaNhan extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'nguoi_dung_ca_nhan';
    public $timestamps = false;
    protected $fillable = [
        'ho_ten',
        'email',
        'mat_khau',
        'ngay_sinh',
        'gioi_tinh'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
