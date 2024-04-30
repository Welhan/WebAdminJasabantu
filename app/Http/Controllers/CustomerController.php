<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;
use Exception;

class CustomerController extends Controller
{
    function index()
    {
        // $client = new Client();
        // $response = $client->get('https://bkgkgngv-5000.asse.devtunnels.ms/api/getUser', [
        //     'curl' => [
        //         CURLOPT_SSL_VERIFYPEER => false, // Disable for self-signed certificates (if needed)
        //         CURLOPT_RETURNTRANSFER => true
        //     ]
        // ]);

        // $bodyContent = $response->getBody()->getContents();
        // $dataArray = json_decode($bodyContent, true);
        // return $dataArray;

        $data = [
            'title' => 'Customer Management',
        ];
        return view("customer.index", $data);
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $tabledata = view('customer.tableData')->render();
            return response()->json($tabledata);
        } else {
            return redirect('customer-management');
        }
    }

    function tableData(Request $request)
    {
        if ($request->ajax()) {

            $param['draw'] = isset($_REQUEST['draw']) ? $_REQUEST['draw'] : '';
            // $keySearch = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value'] : '';
            // $start = isset($_REQUEST['start']) ? $_REQUEST['start'] : '';
            // $length = isset($_REQUEST['length']) ? $_REQUEST['length'] : '';

            $client = new Client();
            try {
                $url = env('URL_API') . '/api/getUser';
                $response = $client->get($url, [
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
            return redirect('customer-management');
        }
    }

    function editCustomer(Request $request)
    {
        if ($request->ajax()) {
            $view = view('customer/modals/editCustomer')->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect('customer-management');
        }
    }

    function deleteCustomer(Request $request)
    {
        if ($request->ajax()) {
            $view = view('customer/modals/deleteCustomer')->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect('customer-management');
        }
    }
}
