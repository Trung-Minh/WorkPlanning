<!-- app/Models/CongViec.php -->

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CongViec extends Model
{
  use HasFactory;

  protected $table = 'cong_viec';
  protected $primaryKey = 'ID_CV';
  public $timestamps = false;

  protected $fillable = [
    'ID_KH',
    'ID_CAUHINH',
    'TRANG_THAI_CV',
    'NOI_DUNG_CV',
    'TIME_START',
    'TIME_END',
    'TIEN_DO',
    'DO_UU_TIEN'
  ];

  public function keHoach()
  {
    return $this->belongsTo(KeHoach::class, 'ID_KH', 'ID_KH');
  }

  public function cauHinhThongBao()
  {
    return $this->belongsTo(CauHinhThongBao::class, 'ID_CAUHINH', 'ID_CAUHINH');
  }
}
?>