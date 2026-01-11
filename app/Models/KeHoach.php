<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class KeHoach extends Model
{
    use HasFactory;

    protected $table = 'KE_HOACH';

    protected $keyType = 'string'; //đinh dạng chuỗi cho laravel hiểu ID_KH

    public $incrementing = false;

    protected $primaryKey = 'ID_KH';

    public $timestamps = false;

    protected $fillable = [
        'TEN_KE_HOACH',
        'NGUOI_TAO',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'NGUOI_TAO', 'ID_USER');
    }

    public function congViecs()
    {
        return $this->hasMany(CongViec::class, 'ID_KH', 'ID_KH');
    }

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDungCaNhan::class, 'NGUOI_TAO', 'ID_USER');
    }

    protected static function booted() {
        static::creating(function ($model) {
            $maxId = DB::table('KE_HOACH')
                ->select(DB::raw('MAX(CAST(SUBSTRING(ID_KH, 3) AS UNSIGNED)) as max_val'))
                ->value('max_val') ?? 0;
            $model->ID_KH = 'KH' . str_pad($maxId + 1, 6, '0', STR_PAD_LEFT);
        });
    }
}
