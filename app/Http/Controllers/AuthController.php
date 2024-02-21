<?php

namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    function index()
    {
        $data = [
            'title' => 'Halaman Login'
        ];
        return view("auth.login", $data);
    }

    function login(Request $request)
    {
        if ($request->ajax()) {
            $url = env('URL_API_MITRA') . 'api/loginAdmin';

            $data = [
                $request->Email,
                $request->Password,
            ];

            $datamitra = implode(env('DELIMITTER_ADMIN'), $data);
            $rot15 = rot15($datamitra);
            $datalogin = base64_encode($rot15);
            $token = env('PREFIX_KEY') . $datalogin;

            try {
                $client = new Client();
                $response = $client->post($url, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json', // Specify expected response format
                        'Authorization' => "Bearer $token",
                    ],
                ]);

                $responseBody = $response->getBody()->getContents();
                $message = json_decode($responseBody, true);

                if ($response->getStatusCode() === 200) {
                    session()->put('name', $message['session']['name']);
                    session()->put('token', $message['session']['email']);
                    $msg = [
                        'success' => 'success'
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
            return redirect('login');
        }
    }

    function logout()
    {
        $url = env('URL_API_MITRA') . 'api/logoutAdmin';
        $token =  session()->get('token');
        $client = new Client();
        $response = $client->post($url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json', // Specify expected response format
                'Authorization' => "Bearer $token",
            ],
        ]);

        $responseBody = $response->getBody()->getContents();
        $message = json_decode($responseBody, true);

        Session::flush();

        Session::flash('message', $message['message']);
        Session::flash('alert', 'alert-success');
        return redirect('login');
    }
}
