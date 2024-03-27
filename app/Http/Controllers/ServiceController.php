<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;

class ServiceController extends Controller
{
    function index()
    {
        $data = [
            'title' => 'Service',
        ];
        return view("service.index", $data);
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

            $tabledata = view('service.tableData')->render();
            return response()->json($tabledata);
        }
    }

    function tableData(Request $request)
    {
        if ($request->ajax()) {

            $param['draw'] = isset($_REQUEST['draw']) ? $_REQUEST['draw'] : '';
            // $keySearch = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value'] : '';
            $start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
            $length = isset($_REQUEST['length']) ? $_REQUEST['length'] : 0;

            $msg = [
                'draw' => intval($param['draw']),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ];

            return response()->json($msg, 200);
        } else {
            return redirect('service');
        }
    }
}
