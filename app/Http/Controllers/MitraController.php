<?php

namespace App\Http\Controllers;

use App\Models\MitraModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;

class MitraController extends Controller
{
    function index()
    {
        // $client = new Client();
        // $response = $client->get('https://bkgkgngv-5000.asse.devtunnels.ms/api/getMitra', [
        //     'curl' => [
        //         CURLOPT_SSL_VERIFYPEER => false, // Disable for self-signed certificates (if needed)
        //         CURLOPT_RETURNTRANSFER => true
        //     ]
        // ]);

        // $bodyContent = $response->getBody()->getContents();
        // $dataArray = json_decode($bodyContent, true);
        // return $response;

        $data = [
            'title' => 'Mitra Management',
        ];
        return view("mitra.index", $data);
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $tabledata = view('mitra.tableData')->render();
            return response()->json($tabledata);
        }
    }

    function tableData(Request $request)
    {
        if ($request->ajax()) {

            $param['draw'] = isset($_REQUEST['draw']) ? $_REQUEST['draw'] : '';
            // $keySearch = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value'] : '';
            // $start = isset($_REQUEST['start']) ? $_REQUEST['start'] : '';
            // $length = isset($_REQUEST['length']) ? $_REQUEST['length'] : '';

            // // $mitra =  MitraModel::getMitra($start, $length);


            try {
                $client = new Client();
                $response = $client->get('https://bkgkgngv-5000.asse.devtunnels.ms/api/getMitra', [
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => false, // Disable for self-signed certificates (if needed)
                        CURLOPT_RETURNTRANSFER => true
                    ]
                ]);

                if ($response) {
                    if ($response->getStatusCode() === 200) {
                        $bodyContent = $response->getBody()->getContents();
                        $dataArray = json_decode($bodyContent, true);
                        $summary = count($dataArray['data']);
                        $msg = [
                            'draw' => intval($param['draw']),
                            'recordsTotal' => $summary,
                            'recordsFiltered' => $summary,
                            'data' => $dataArray['data']
                        ];
                    } else {
                        $msg = [
                            'draw' => intval($param['draw']),
                            'recordsTotal' => 0,
                            'recordsFiltered' => 0,
                            'data' => []
                        ];
                    }

                    return response()->json($msg, 200);
                } else {
                    $msg = [
                        'draw' => intval($param['draw']),
                        'recordsTotal' => 0,
                        'recordsFiltered' => 0,
                        'data' => []
                    ];

                    return response()->json($msg, 200);
                }
            } catch (Exception $e) {
                $msg = [
                    'draw' => intval($param['draw']),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => []
                ];

                return response()->json($msg, 200);
            }
        } else {
            return redirect()->route('/mitra-management');
        }
    }

    function newMitra(Request $request)
    {
        if ($request->ajax()) {
            $view = view('mitra/modals/newMitra')->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect()->route('mitra-management');
        }
    }

    // public function store(Request $request)
    // {
    //     if ($request->ajax()) {

    //         $validator = Validator::make($request->all(), [
    //             'name' => 'required|string|max:255',
    //             'email' => 'required|email',
    //             'phone' => 'required',
    //             'address' => 'required',
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json(['errors' => $validator->errors()->toArray()], 422); // Return validation errors with 422 status code
    //         } else {
    //             $process = MitraModel::create([
    //                 'Name' => $request->name,
    //                 'Email' => $request->email,
    //                 'Phone' => $request->phone,
    //                 'Address' => $request->address,
    //             ]);

    //             if ($process) {
    //                 Session::flash('message', 'User Created Successfully');
    //                 Session::flash('alert', 'alert-success');
    //                 $msg = [
    //                     'success' => 'User created successfully.'
    //                 ];
    //             } else {
    //                 Session::flash([
    //                     'message' => 'Create User Failed',
    //                     'alert' => 'alert-danger'
    //                 ]);
    //                 $msg = [
    //                     'failed' => 'Create User Failed'
    //                 ];
    //             }
    //             return response()->json($msg);
    //         }
    //     }
    // }

    function editMitra(Request $request)
    {
        if ($request->ajax()) {
            $view = view('mitra/modals/editMitra')->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect()->route('mitra-management');
        }
    }
}
