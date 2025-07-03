<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KeHoach;
use App\Models\CongViec;
use Illuminate\Support\Facades\Auth;

class PlansController extends Controller
{
  public function index()
  {
    $userId = Auth::user()->id;
    $keHoachs = KeHoach::with('congViecs')->where('NGUOI_TAO', $userId)->get();
    return view('kehoach', compact('keHoachs'));
  }

  public function store(Request $request)
  {
    $request->validate([
      'TEN_KE_HOACH' => 'required|string|max:255',
    ]);

    $keHoach = KeHoach::create([
      'TEN_KE_HOACH' => $request->TEN_KE_HOACH,
      'NGUOI_TAO' => Auth::id(),
    ]);

    return response()->json($keHoach);
  }

  public function storeTask(Request $request)
  {
    $request->validate([
      'ID_KH' => 'required|exists:ke_hoach,ID_KH',
      'NOI_DUNG_CV' => 'required',
    ]);

    $cv = CongViec::create([
      'ID_KH' => $request->ID_KH,
      'NOI_DUNG_CV' => $request->NOI_DUNG_CV,
      'TIME_START' => $request->TIME_START,
      'TIME_END' => $request->TIME_END,
      'TIEN_DO' => $request->TIEN_DO ?? 0,
      'TRANG_THAI_CV' => $request->TRANG_THAI_CV ?? 'chua_lam',
      'DO_UU_TIEN' => $request->DO_UU_TIEN ?? 0,
    ]);

    return response()->json($cv);
  }

  public function destroyTask($id)
  {
    $cv = CongViec::findOrFail($id);
    $cv->delete();

    return response()->json(['message' => 'Xóa thành công']);
  }
}
