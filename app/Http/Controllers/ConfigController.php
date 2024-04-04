<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\ConfigModel;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ConfigController extends Controller
{
    function index()
    {
        $data = [
            'title' => 'Config Web',
        ];
        return view("config.index", $data);
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

            $tabledata = view('config.tableData')->render();
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


            $config = ConfigModel::getConfig($start, $length);
            $summary =  ConfigModel::summaryConfig();

            $msg = [
                'draw' => intval($param['draw']),
                'recordsTotal' => $summary,
                'recordsFiltered' => $summary,
                'data' => $config
            ];

            return response()->json($msg, 200);
        } else {
            return redirect('web-config');
        }
    }

    function newConfig(Request $request)
    {
        if ($request->ajax()) {
            $view = view('config/modals/newConfig')->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect('web-config');
        }
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'config' => 'required|string|max:255',
                'value' => 'required',
            ]);

            $config = $request->config;
            $value = $request->value;


            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->toArray()], 422); // Return validation errors with 422 status code
            } else {

                $data = [
                    'Config' => $config,
                    'Value' => $value,
                    'ActiveF' => 1,
                    'CDate' => date('Y-m-d H-i-s'),
                    'CUserID' => 1,
                ];


                $process = ConfigModel::saveConfig($data);

                if ($process) {
                    Session::flash('message', 'Config Berhasil Dibuat');
                    Session::flash('alert', 'alert-success');
                    $msg = [
                        'success' => 'Config Berhasil Dibuat'
                    ];
                } else {
                    Session::flash('message', 'Config Gagal Dibuat');
                    Session::flash('alert', 'alert-danger');
                    $msg = [
                        'failed' => 'Config Gagal Dibuat'
                    ];
                }


                return response()->json($msg);
            }
        } else {
            return redirect('web-config');
        }
    }

    function editConfig(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;

            $config = ConfigModel::findOrFail($id);
            $data = [
                'config' => $config
            ];
            $view = view('config/modals/editConfig', $data)->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect('web-config');
        }
    }

    function update(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'config' => 'required|string|max:255',
                'value' => 'required',
            ]);

            $config = $request->config;
            $value = $request->value;
            $activeF = $request->ActiveF;

            $id = $request->ID;


            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->toArray()], 422); // Return validation errors with 422 status code
            } else {
                $data = [
                    'Config' => $config,
                    'Value' => $value,
                    'ActiveF' => is_null($activeF) ? '0' : '1',
                    'Last_Date' => date('Y-m-d H-i-s'),
                    'Last_User' => 1,
                ];


                $process = ConfigModel::updateConfig($id, $data);
                if ($process) {
                    Session::flash('message', 'Config Berhasil Diupdate');
                    Session::flash('alert', 'alert-success');
                    $msg = [
                        'success' => 'Config Berhasil Diupdate'
                    ];
                } else {
                    Session::flash('message', 'Config Gagal Diupdate');
                    Session::flash('alert', 'alert-danger');
                    $msg = [
                        'failed' => 'Config Gagal Diupdate'
                    ];
                }

                return response()->json($msg);
            }
        } else {
            return redirect('web-config');
        }
    }

    function deleteConfig(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $config = ConfigModel::findOrFail($id);
            $data = [
                'config' => $config
            ];

            $view = view('config/modals/deleteConfig', $data)->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect('web-config');
        }
    }

    public function delete(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->ID;
            $process = ConfigModel::deleteConfig($id);

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

            return response()->json($msg);
        } else {
            return redirect('web-config');
        }
    }
}
