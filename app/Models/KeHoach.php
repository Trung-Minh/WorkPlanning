<!-- app/Models/KeHoach.php -->

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KeHoach extends Model
{
  use HasFactory;

  protected $table = 'ke_hoach';
  protected $primaryKey = 'ID_KH';
  public $timestamps = false;

  protected $fillable = [
    'TEN_KE_HOACH',
    'NGUOI_TAO'
  ];

  public function congViecs()
  {
    return $this->hasMany(CongViec::class, 'ID_KH', 'ID_KH');
  }

  public function nguoiDung()
  {
    return $this->belongsTo(NguoiDungCaNhan::class, 'NGUOI_TAO', 'ID_USER');
  }
}
?>