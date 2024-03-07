<?php

namespace App\Http\Controllers;

use App\Models\MidtransModel;
use App\Models\UserModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;

class MidtransController extends Controller
{
    function index()
    {
        $data = [
            'title' => 'Config Midtrans',
        ];
        return view("midtrans.index", $data);
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

            $tabledata = view('midtrans.tableData')->render();
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


            $midtrans = MidtransModel::getMidtrans($start, $length);
            $summary =  MidtransModel::summaryMidtrans();

            $msg = [
                'draw' => intval($param['draw']),
                'recordsTotal' => $summary,
                'recordsFiltered' => $summary,
                'data' => $midtrans
            ];

            return response()->json($msg, 200);
        } else {
            return redirect('midtrans');
        }
    }

    function newMidtrans(Request $request)
    {
        if ($request->ajax()) {
            $view = view('midtrans/modals/newMidtrans')->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect('midtrans');
        }
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'Config' => 'required|string|max:255',
                'Value1' => 'required',
                'Value2' => 'required',
                'Type' => 'required',
                'Desc' => 'required',
            ]);

            $config = $request->Config;
            $value1 = $request->Value1;
            $value2 = $request->Value2;
            $type = $request->Type;
            $desc = $request->Desc;


            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->toArray()], 422); // Return validation errors with 422 status code
            } else {

                $data = [
                    'Config' => $config,
                    'Value_1' => $value1,
                    'Value_2' => $value2,
                    'Type' => $type,
                    'Desc' => $desc,
                    'AllowedF' => 1,
                    'CDate' => date('Y-m-d H-i-s'),
                    'CUserID' => 1,
                ];


                $process = MidtransModel::saveMidtrans($data);

                if ($process) {
                    Session::flash('message', 'Config Midtrans Berhasil Dibuat');
                    Session::flash('alert', 'alert-success');
                    $msg = [
                        'success' => 'Config Midtrans Berhasil Dibuat'
                    ];
                } else {
                    Session::flash('message', 'Config Midtrans Gagal Dibuat');
                    Session::flash('alert', 'alert-danger');
                    $msg = [
                        'failed' => 'Config Midtrans Gagal Dibuat'
                    ];
                }


                return response()->json($msg);
            }
        } else {
            return redirect('mitra-management');
        }
    }

    function editMidtrans(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;

            $midtrans = MidtransModel::findOrFail($id);
            $data = [
                'midtrans' => $midtrans
            ];
            $view = view('midtrans/modals/editMidtrans', $data)->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect('midtrans');
        }
    }

    function update(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'Config' => 'required|string|max:255',
                'Value1' => 'required',
                'Value2' => 'required',
                'Type' => 'required',
                'Desc' => 'required',
            ]);

            $config = $request->Config;
            $value1 = $request->Value1;
            $value2 = $request->Value2;
            $type = $request->Type;
            $desc = $request->Desc;

            $id = $request->ID;


            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->toArray()], 422); // Return validation errors with 422 status code
            } else {
                $data = [
                    'Config' => $config,
                    'Value_1' => $value1,
                    'Value_2' => $value2,
                    'Type' => $type,
                    'Desc' => $desc,
                    'AllowedF' => 1,
                    'Last_Date' => date('Y-m-d H-i-s'),
                    'Last_User' => 1,
                ];


                $process = MidtransModel::updateMidtrans($id, $data);
                if ($process) {
                    Session::flash('message', 'Config Midtrans Berhasil Diupdate');
                    Session::flash('alert', 'alert-success');
                    $msg = [
                        'success' => 'Config Midtrans Berhasil Diupdate'
                    ];
                } else {
                    Session::flash('message', 'Config Midtrans Gagal Diupdate');
                    Session::flash('alert', 'alert-danger');
                    $msg = [
                        'failed' => 'Config Midtrans Gagal Diupdate'
                    ];
                }

                return response()->json($msg);
            }
        } else {
            return redirect('midtrans');
        }
    }

    function deleteMidtrans(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $midtrans = MidtransModel::findOrFail($id);
            $data = [
                'midtrans' => $midtrans
            ];

            $view = view('midtrans/modals/deleteMidtrans', $data)->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect('midtrans');
        }
    }

    public function delete(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->ID;
            $process = MidtransModel::deleteMidtrans($id);

            if ($process) {
                Session::flash('message', 'Data Config Berhasil Dihapus');
                Session::flash('alert', 'alert-success');
                $msg = [
                    'success' => 'Data Config Berhasil Dihapus'
                ];
            } else {
                Session::flash('message', 'Data Config Gagal Dihapus');
                Session::flash('alert', 'alert-danger');
                $msg = [
                    'failed' => 'Data Config Gagal Dihapus'
                ];
            }
        } else {
            return redirect('midtrans');
        }
    }
}
