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

        // $encodedString = "Q0NEZWRoRGRmZWVjdlZVTA=="; 
        // $decodedString = base64_decode($encodedString);

        // $rot15 = reverseRot15($decodedString);
        // return $rot15;

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
            $start = isset($_REQUEST['start']) ? $_REQUEST['start'] : '';
            $length = isset($_REQUEST['length']) ? $_REQUEST['length'] : '';

            $mitra =  MitraModel::getMitra($start, $length);
            $summary =  MitraModel::summaryMitra();


            $msg = [
                'draw' => intval($param['draw']),
                'recordsTotal' => $summary,
                'recordsFiltered' => $summary,
                'data' => $mitra
            ];
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
                    echo "Registration failed due to an error: " . $e->getMessage();
                }

                return response()->json($msg);
            }
        }
    }

    function editMitra(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $data = [
                'mitra' => MitraModel::findOrFail($id)
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
}
