<?php

namespace App\Http\Controllers;

use App\Models\MitraModel;
use App\Models\UserModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;

class MitraController extends Controller
{
    function index()
    {
        // $url = env('URL_API') . 'api/loginAdmin';

        // $data = [
        //     'admin@gmail.com',
        //     'admin'
        // ];

        // $datamitra = implode("#", $data);
        // $rot15 = rot15($datamitra);
        // $token = base64_encode($rot15);

        // $client = new Client();
        // $response = $client->post($url, [
        //     'headers' => [
        //         'Content-Type' => 'application/json',
        //         'Accept' => 'application/json', // Specify expected response format
        //         'Authorization' => "Bearer $token",
        //     ],
        // ]);

        // $responseBody = $response->getBody()->getContents();
        // $status = $response->getStatusCode();
        // $message = json_decode($responseBody, true);

        // return $message;

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
            $keySearch = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value'] : '';
            $start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
            $length = isset($_REQUEST['length']) ? $_REQUEST['length'] : 0;

            $client = new Client();
            try {
                $params = [
                    'start' => $start,
                    'length' => $length
                ];
                $token = session()->get('token');
                $url = env('URL_API') . '/api/getMitra';
                $response = $client->post($url, [
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => false, // Disable for self-signed certificates (if needed)
                        CURLOPT_RETURNTRANSFER => true
                    ],
                    'json' => $params,
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json', // Specify expected response format
                        'Authorization' => "Bearer $token",
                    ],
                ]);

                if ($response) {
                    if ($response->getStatusCode() === 200) {
                        $bodyContent = $response->getBody()->getContents();
                        $dataArray = json_decode($bodyContent, true);
                        $datamitra = $dataArray['data'];
                        $summary = $dataArray['summary'];

                        $msg = [
                            'draw' => intval($param['draw']),
                            'recordsTotal' => $summary,
                            'recordsFiltered' => $summary,
                            'data' => $datamitra
                        ];
                    } else {
                        $msg = [
                            'draw' => intval($param['draw']),
                            'recordsTotal' => 0,
                            'recordsFiltered' => 0,
                            'data' => []
                        ];
                    }
                } else {
                    $msg = [
                        'draw' => intval($param['draw']),
                        'recordsTotal' => 0,
                        'recordsFiltered' => 0,
                        'data' => []
                    ];
                }
            } catch (Exception $e) {
                $msg = [
                    'draw' => intval($param['draw']),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => []
                ];
            }
            return response()->json($msg, 200);
        } else {
            return redirect()->route('/mitra-management');
        }
    }

    function generatePin(Request $request)
    {
        if ($request->ajax()) {

            $msg = [
                'number' => mt_rand(000000, 999999)
            ];

            return response()->json($msg, 200);
        } else {
            return redirect()->route('/mitra-management');
        }
    }

    function getUser(Request $request)
    {
        if ($request->ajax()) {

            $user = $request->User;
            $data = UserModel::getDataUser($user);

            return response()->json($data, 200);
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

    public function store(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'Name' => 'required|string|max:255',
                'Email' => 'required|email',
                'Phone' => 'required',
                'Address' => 'required',
                'Pin' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->toArray()], 422); // Return validation errors with 422 status code
            } else {
                $url = env('URL_API') . 'api/registerMitra';

                $data = [
                    $request->Name,
                    $request->Email,
                    $request->Phone,
                    $request->Address,
                    $request->Pin,
                ];

                $datamitra = implode(":", $data);
                $rot15 = rot15($datamitra);
                $mitra = base64_encode($rot15);

                try {
                    $client = new Client();
                    $response = $client->post($url, [
                        'headers' => [
                            'Content-Type' => 'application/json',
                            'Accept' => 'application/json', // Specify expected response format
                            'Authorization' => "Bearer $mitra",
                        ],
                    ]);

                    $responseBody = $response->getBody()->getContents();
                    $message = json_decode($responseBody, true);

                    if ($response->getStatusCode() === 200) {
                        Session::flash('message', $message['message']);
                        Session::flash('alert', 'alert-success');
                        $msg = [
                            'success' => $message['message']
                        ];
                    } else {
                        Session::flash([
                            'message' => $message['message'],
                            'alert' => 'alert-danger'
                        ]);
                        $msg = [
                            'failed' => $message['message']
                        ];
                    }
                } catch (Exception $e) {
                    // Handle network errors or other exceptions
                    echo "Error: " . $e->getMessage();
                }

                return response()->json($msg);
            }
        }
    }

    function editMitra(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $url = env('URL_API') . '/api/getMitra';
            $client = new Client();
            $response = $client->get($url);

            $bodyContent = $response->getBody()->getContents();
            $dataArray = json_decode($bodyContent, true);
            $datamitra = $dataArray['data'];

            $mitra = collect($datamitra)->filter(function ($datamitra) {
                return $datamitra['UniqueID'];
            });

            $data = [
                'mitra' => $mitra
            ];
            $view = view('mitra/modals/editMitra', $data)->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect()->route('mitra-management');
        }
    }

    public function update(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'Name' => 'required|string|max:255',
                'Email' => 'required|email',
                'Phone' => 'required',
                'Address' => 'required',
            ]);

            $uniqueid = $request->Uniqueid;

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->toArray()], 422); // Return validation errors with 422 status code
            } else {
                $data = [
                    'Name' => $request->Name,
                    'Email' => $request->Email,
                    'Phone' => $request->Phone,
                    'Pin' => $request->Pin,
                    'Address' => $request->Address,
                    'CreatedDate' => date('Y-m-d H-i-s'),
                ];

                $process = MitraModel::updateMitra($uniqueid, $data);
                if ($process) {
                    Session::flash('message', 'Mitra Updated Successfully');
                    Session::flash('alert', 'alert-success');
                    $msg = [
                        'success' => 'Mitra created successfully.'
                    ];
                } else {
                    Session::flash('message', 'Mitra Update Failed');
                    Session::flash('alert', 'alert-danger');
                    $msg = [
                        'failed' => 'Mitra Update Failed'
                    ];
                }
                return response()->json($msg);
            }
        }
    }

    function deleteMitra(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $data = [
                'mitra' => MitraModel::findOrFail($id)
            ];

            $view = view('mitra/modals/deleteMitra', $data)->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect()->route('mitra-management');
        }
    }

    public function delete(Request $request)
    {
        if ($request->ajax()) {
            $uniqueid = $request->Uniqueid;
            $process = MitraModel::deleteMitra($uniqueid);

            if ($process) {
                Session::flash('message', 'Data Mitra Has Been Deleted!');
                Session::flash('alert', 'alert-success');
                $msg = [
                    'success' => 'Data Mitra Has Been Deleted!'
                ];
            } else {
                Session::flash('message', 'Delete Mitra Failed');
                Session::flash('alert', 'alert-danger');
                $msg = [
                    'failed' => 'Delete Mitra Failed'
                ];
            }
            return response()->json($msg);
        }
    }

    function formResetPin(Request $request)
    {
        if ($request->ajax()) {

            $id = $request->id;
            $data = [
                'id' => $id
            ];

            $view = view('mitra/modals/resetPin', $data)->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect()->route('mitra-management');
        }
    }
}
