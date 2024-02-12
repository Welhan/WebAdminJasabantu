<?php

namespace App\Http\Controllers;

use App\Models\MitraModel;
use Illuminate\Http\Request;

class MitraController extends Controller
{
    function index()
    {
        $getMitra = MitraModel::getMitra();
        $data = [
            'title' => 'Mitra Management',
        ];
        return view("mitra.index", $data);
    }
    function getDataMitra(Request $request)
    {
        if($request->ajax()){
            $getMitra = MitraModel::getMitra();
            $data = [
                'data' => $getMitra
            ];
            return response()->json($data, 200);
        }else{
            return redirect()->route('/mitra-management');
        }
    }
    function newMitra(Request $request)
    {
        if($request->ajax()){
            $view = view('mitra/modals/newMitra')->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect()->route('mitra-management');
        }
    }
}
