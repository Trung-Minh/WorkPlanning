<?php

namespace App\Http\Controllers;

use App\Models\KeHoach;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->ID_USER;

        $reminders = KeHoach::with('congViecs.mucCongViecs')
        ->where('NGUOI_TAO', $userId)
        ->get();

        //dd($reminders->first()->ID_KH);
        
        //$id_kh_user = $reminders->ID_KH;
        //$query = CongViec::where('ID_KH', $id_kh_user)
           //->get();
        //$row = $query -> first();
        //echo $row;

        //return view('reminders', compact('reminders', 'query'));
        return view('reminders', compact('reminders'));
    }
    //nguyenthaianh
   
}
