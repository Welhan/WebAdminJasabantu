<?php

namespace App\Http\Controllers;

use App\Models\MerchantModel;
use App\Models\UserModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;

class MerchantController extends Controller
{
    function index()
    {
        $data = [
            'title' => 'Merchant Management',
        ];
        return view("merchant.index", $data);
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

            $data = [
                'email' => $request->email,
                'phone' => $request->phone,
                'name' => $request->name,
            ];

            $tabledata = view('merchant.tableData', $data)->render();
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

            $email = $request->email ? $request->email : "";
            $phone = $request->phone ? $request->phone  : "";
            $name = $request->name ? $request->name : "";

            $client = new Client();
            try {
                $params = [
                    'start' => $start,
                    'length' => $length,
                    'email' => $email,
                    'phone' => $phone,
                    'name' => $name,
                ];
                $token = session()->get('token');
                $url = env('URL_API_MITRA') . '/api/getMitra';
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
            return redirect('merchant-management');
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
            return redirect('merchant-management');
        }
    }

    function getUser(Request $request)
    {
        if ($request->ajax()) {

            $user = $request->User;
            $data = UserModel::getDataUser($user);

            return response()->json($data, 200);
        } else {
            return redirect('merchant-management');
        }
    }

    function newMerchant(Request $request)
    {
        if ($request->ajax()) {
            $view = view('merchant/modals/newMerchant')->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect('merchant-management');
        }
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'Name' => 'required|string|max:255',
                'Email' => 'required|email',
                'Phone' => 'required|numeric',
                'Address' => 'required',
                'Pin' => 'required',
            ]);

            $name = $request->Name;
            $email = $request->Email;
            $phone = $request->Phone;
            $address = $request->Address;
            $pin = $request->Pin;

            $rotemail = rot15(env('DELIMITTER_ADMIN') . $email);
            $tokenemail = session()->get('token') . base64_encode($rotemail);
            $urlemail = env('URL_API_MITRA') . '/api/checkEmailMitra';
            $client = new Client();
            $response = $client->post($urlemail, [
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => false, // Disable for self-signed certificates (if needed)
                    CURLOPT_RETURNTRANSFER => true
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json', // Specify expected response format
                    'Authorization' => "Bearer $tokenemail",
                ],
            ]);

            $bodyemail = $response->getBody()->getContents();
            $remail = json_decode($bodyemail, true);

            $rotphone = rot15(env('DELIMITTER_ADMIN') . $phone);
            $token = session()->get('token') . base64_encode($rotphone);
            $urlphone = env('URL_API_MITRA') . '/api/checkPhoneMitra';
            $client = new Client();
            $response = $client->post($urlphone, [
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => false, // Disable for self-signed certificates (if needed)
                    CURLOPT_RETURNTRANSFER => true
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json', // Specify expected response format
                    'Authorization' => "Bearer $token",
                ],
            ]);

            $bodyphone = $response->getBody()->getContents();
            $rphone = json_decode($bodyphone, true);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->toArray()], 422); // Return validation errors with 422 status code
            } else if ($remail['status'] == 'failed' && $rphone['status'] == 'failed') {
                $data = [
                    'Email' => $remail['message'],
                    'Phone' => $rphone['message'],
                ];
                return response()->json(['errors' => $data], 422);
            } else if ($remail['status'] == 'failed') {
                $data = [
                    'Email' => $remail['message'],
                ];
                return response()->json(['errors' => $data], 422);
            } else if ($rphone['status'] == 'failed') {
                $data = [
                    'Phone' => $rphone['message'],
                ];
                return response()->json(['errors' => $data], 422);
            } else {
                $url = env('URL_API_MITRA') . 'api/registerMitra';

                $data = [
                    env('DELIMITTER_ADMIN') . $name,
                    $email,
                    $phone,
                    $address,
                    $pin,
                ];

                $datamitra = implode(env('DELIMITTER_ADMIN'), $data);
                $rot15 = rot15($datamitra);
                $mitra = session()->get('token') . base64_encode($rot15);

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
                        Session::flash('message', $message['message']);
                        Session::flash('alert', 'alert-danger');
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
        } else {
            return redirect('merchant-management');
        }
    }

    function editMerchant(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $rot15 = rot15(env('DELIMITTER_ADMIN') . $id);
            $token = session()->get('token') . base64_encode($rot15);
            $url = env('URL_API_MITRA') . '/api/getDataMitra';
            $client = new Client();
            $response = $client->post($url, [
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => false, // Disable for self-signed certificates (if needed)
                    CURLOPT_RETURNTRANSFER => true
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json', // Specify expected response format
                    'Authorization' => "Bearer $token",
                ],
            ]);

            $bodyContent = $response->getBody()->getContents();
            $dataArray = json_decode($bodyContent, true);
            $mitra = $dataArray['data'];

            $data = [
                'mitra' => $mitra,
                'id' => $id,
            ];
            $view = view('merchant/modals/editMerchant', $data)->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect('merchant-management');
        }
    }

    function checkEmail(Request $request)
    {
        if ($request->ajax()) {

            $email = $request->email;
            $id = $request->id;

            $rot15 = rot15(env('DELIMITTER_ADMIN') . $email . env('DELIMITTER_ADMIN') . $id);
            $token = session()->get('token') . base64_encode($rot15);
            $url = env('URL_API_MITRA') . '/api/checkEmailMitra';
            $client = new Client();
            $response = $client->post($url, [
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => false, // Disable for self-signed certificates (if needed)
                    CURLOPT_RETURNTRANSFER => true
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json', // Specify expected response format
                    'Authorization' => "Bearer $token",
                ],
            ]);

            $bodyContent = $response->getBody()->getContents();
            $dataemail = json_decode($bodyContent, true);
            $msg = [
                'status' => $dataemail['status'],
                'message' => $dataemail['message']
            ];

            return response()->json($msg, 200);
        } else {
            return redirect('merchant-management');
        }
    }

    function checkPhone(Request $request)
    {
        if ($request->ajax()) {

            $phone = $request->phone;
            $id = $request->id;

            $rot15 = rot15(env('DELIMITTER_ADMIN') . $phone . env('DELIMITTER_ADMIN') . $id);
            $token = session()->get('token') . base64_encode($rot15);
            $url = env('URL_API_MITRA') . '/api/checkPhoneMitra';
            $client = new Client();
            $response = $client->post($url, [
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => false, // Disable for self-signed certificates (if needed)
                    CURLOPT_RETURNTRANSFER => true
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json', // Specify expected response format
                    'Authorization' => "Bearer $token",
                ],
            ]);

            $bodyContent = $response->getBody()->getContents();
            $dataphone = json_decode($bodyContent, true);
            $msg = [
                'status' => $dataphone['status'],
                'message' => $dataphone['message']
            ];

            return response()->json($msg, 200);
        } else {
            return redirect('merchant-management');
        }
    }

    function update(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'Name' => 'required|string|max:255',
                'Email' => 'required|email',
                'Phone' => 'required',
                'Address' => 'required',
            ]);

            $uniqueid = $request->Uniqueid;
            $name = $request->Name;
            $email = $request->Email;
            $phone = $request->Phone;
            $address = $request->Address;


            $rotemail = rot15(env('DELIMITTER_ADMIN') . $email . env('DELIMITTER_ADMIN') . $uniqueid);
            $tokenemail = session()->get('token') . base64_encode($rotemail);
            $urlemail = env('URL_API_MITRA') . '/api/checkEmailMitra';
            $client = new Client();
            $response = $client->post($urlemail, [
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => false, // Disable for self-signed certificates (if needed)
                    CURLOPT_RETURNTRANSFER => true
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json', // Specify expected response format
                    'Authorization' => "Bearer $tokenemail",
                ],
            ]);

            $bodyemail = $response->getBody()->getContents();
            $remail = json_decode($bodyemail, true);

            $rotphone = rot15(env('DELIMITTER_ADMIN') . $phone . env('DELIMITTER_ADMIN') . $uniqueid);
            $token = session()->get('token') . base64_encode($rotphone);
            $urlphone = env('URL_API_MITRA') . '/api/checkPhoneMitra';
            $client = new Client();
            $response = $client->post($urlphone, [
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => false, // Disable for self-signed certificates (if needed)
                    CURLOPT_RETURNTRANSFER => true
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json', // Specify expected response format
                    'Authorization' => "Bearer $token",
                ],
            ]);

            $bodyphone = $response->getBody()->getContents();
            $rphone = json_decode($bodyphone, true);


            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->toArray()], 422); // Return validation errors with 422 status code
            } else if ($remail['status'] == 'failed' && $rphone['status'] == 'failed') {
                $data = [
                    'Email' => $remail['message'],
                    'Phone' => $rphone['message'],
                ];
                return response()->json(['errors' => $data], 422);
            } else if ($remail['status'] == 'failed') {
                $data = [
                    'Email' => $remail['message'],
                ];
                return response()->json(['errors' => $data], 422);
            } else if ($rphone['status'] == 'failed') {
                $data = [
                    'Phone' => $rphone['message'],
                ];
                return response()->json(['errors' => $data], 422);
            } else {
                $url = env('URL_API_MITRA') . '/api/updateDataMitra';

                $data = [
                    env('DELIMITTER_ADMIN') . $uniqueid,
                    $name,
                    $email,
                    $phone,
                    $address,
                ];

                $datamitra = implode(env('DELIMITTER_ADMIN'), $data);
                $rot15 = rot15($datamitra);
                $mitra = session()->get('token') . base64_encode($rot15);

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
                        Session::flash('message', $message['message']);
                        Session::flash('alert', 'alert-danger');
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
        } else {
            return redirect('merchant-management');
        }
    }

    function deleteMerchant(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $rot15 = rot15(env('DELIMITTER_ADMIN') . $id);
            $token = session()->get('token') . base64_encode($rot15);
            $url = env('URL_API_MITRA') . '/api/getDataMitra';
            $client = new Client();
            $response = $client->post($url, [
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => false, // Disable for self-signed certificates (if needed)
                    CURLOPT_RETURNTRANSFER => true
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json', // Specify expected response format
                    'Authorization' => "Bearer $token",
                ],
            ]);

            $bodyContent = $response->getBody()->getContents();
            $dataArray = json_decode($bodyContent, true);
            $mitra = $dataArray['data'];
            $data = [
                'mitra' => $mitra,
                'id' => $id
            ];

            $view = view('merchant/modals/deleteMerchant', $data)->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect('merchant-management');
        }
    }

    public function delete(Request $request)
    {
        if ($request->ajax()) {
            $uniqueid = $request->Uniqueid;
            $url = env('URL_API_MITRA') . '/api/deleteMitra';

            $datamitra = env('DELIMITTER_ADMIN') . $uniqueid;
            $rot15 = rot15($datamitra);
            $mitra = session()->get('token') . base64_encode($rot15);

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
                    Session::flash('message', $message['message']);
                    Session::flash('alert', 'alert-danger');
                    $msg = [
                        'failed' => $message['message']
                    ];
                }
            } catch (Exception $e) {
                // Handle network errors or other exceptions
                echo "Error: " . $e->getMessage();
            }

            return response()->json($msg);
        } else {
            return redirect('merchant-management');
        }
    }
}
