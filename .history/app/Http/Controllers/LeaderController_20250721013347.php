<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NguoiDungCaNhan;
use App\Models\Nhom;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\alert;

class LeaderController extends Controller   
{
    public function showLeader(){
        return view('leader');
    }

    public function search_members(Request $r)
    {
        $r->validate([
            'search_members' => 'required',
        ]);

        $user = NguoiDungCaNhan::where('HO_TEN','like',"%{$r->input('search_members')}%")
                                ->where('EMAIL', '!=', Auth::user()->email )
                                ->get();
           

        return redirect()->back()       
        ->withInput()          // <-- flash tất cả inputs
        ->with('invite', value: $user);

    }

    public function addgroup (Request $r)
    {
        $r->validate([
            'id_user' => 'required',
        ]);
        $ngayHomNay = Carbon::now();
        $data = [
            'ID_NHOM_TRUONG' => $r->input('id_user'),
            'NGAY_TAO' => $ngayHomNay,
            'TEN_NHOM' => 'CHƯA CÓ TÊN',
        ];
    
     
        Nhom::create($data);
        $nhom = Nhom::where('ID_NHOM_TRUONG', $r->input('id_user'))
                                ->where('NGAY_TAO', $ngayHomNay)
                                ->where('TEN_NHOM', 'CHƯA CÓ TÊN')->first();

        session(['nhom' => $nhom]);

        return redirect()->route('showLeader') ;
    }

    public function invite (Request $request){
        
        $request->validate([
            'id_user' => 'required|exists:nguoi_dung_ca_nhan,ID_USER',
            'id_nhom' => 'required|exists:nhom_lam_viec,ID_NHOM'
        ]);

        DB::table('loi_moi')->insert([
            'ID_USER' => $request->input('id_user'),
            'ID_NHOM' =>  $request->input('id_nhom'),
        ]);

        return redirect()->back()   ;    
    }
    public function showGroup(){
        return view('group');
    }

    public function doGroup(Request $request){

        $request->validate([
            'id_nhom' => 'required|exists:nhom_lam_viec,ID_NHOM'
        ]);
       
        $nhom = Nhom::where('ID_NHOM', $request->input('id_nhom'))->first();
        session(['group' => $nhom]);

        return redirect()->route('showGroup') ;
    }

     public function doGroups(Request $request){
        alert( $request->ten_nhom.'1');

        $request->validate([
            'ten_nhom' => 'required',
            'id_nhom' => 'required|exists:nhom_lam_viec,ID_NHOM'
        ]);
        alert( $request->ten_nhom.');

        Nhom::where('ID_NHOM', $request->input('id_nhom'))->update(['TEN_NHOM' => $request->ten_nhom]);
        $nhom = Nhom::where('ID_NHOM', $request->input('id_nhom'))->first();
        
        session(['group' => $nhom]);

        return redirect()->route('showGroup') ;
    }

}

