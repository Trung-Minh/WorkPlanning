<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class CauHinhThongBao extends Model
{
  use HasFactory;

  protected $table = 'cau_hinh_thong_bao';
  protected $primaryKey = 'ID_CAUHINH';
  public $timestamps = false;

  protected $fillable = [
    'THOI_GIAN_TRUOC_HAN',
    'NOI_DUNG_TB'
  ];

  public function congViecs()
  {
    return $this->hasMany(CongViec::class, 'ID_CAUHINH', 'ID_CAUHINH');
  }
}

?>