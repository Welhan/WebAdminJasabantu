<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\PaymentModel;
use App\Models\ConfigModel;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use PSpell\Config;

class PaymentController extends Controller
{
    function index()
    {
        $data = [
            'title' => 'Payment Method',
        ];
        return view("payment.index", $data);
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

            $tabledata = view('payment.tableData')->render();
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


            $payment = PaymentModel::getPayment($start, $length);
            $summary =  PaymentModel::summaryPayment();

            $msg = [
                'draw' => intval($param['draw']),
                'recordsTotal' => $summary,
                'recordsFiltered' => $summary,
                'data' => $payment
            ];

            return response()->json($msg, 200);
        } else {
            return redirect('payment-method');
        }
    }

    function newPayment(Request $request)
    {
        if ($request->ajax()) {
            $data = [
                'payment_type' => ConfigModel::where('Config', 'payment_type')->get()
            ];
            $view = view('payment/modals/newPayment', $data)->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect('web-config');
        }
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'Type' => 'required',
                'Value' => 'required',
            ]);

            $type = $request->Type;
            $value = $request->Value;


            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->toArray()], 422); // Return validation errors with 422 status code
            } else {

                $data = [
                    'Payment_type' => $type,
                    'Value' => $value,
                    'ActiveF' => 1,
                    'CDate' => date('Y-m-d H-i-s'),
                    'CUserID' => 1,
                ];


                $process = PaymentModel::savePayment($data);

                if ($process) {
                    Session::flash('message', 'Payment Berhasil Dibuat');
                    Session::flash('alert', 'alert-success');
                    $msg = [
                        'success' => 'Payment Berhasil Dibuat'
                    ];
                } else {
                    Session::flash('message', 'Payment Gagal Dibuat');
                    Session::flash('alert', 'alert-danger');
                    $msg = [
                        'failed' => 'Payment Gagal Dibuat'
                    ];
                }


                return response()->json($msg);
            }
        } else {
            return redirect('payment-method');
        }
    }

    function editPayment(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;

            $payment = PaymentModel::findOrFail($id);

            $data = [
                'payment' => $payment,
                'payment_type' => ConfigModel::where('Config', 'payment_type')->get()
            ];
            $view = view('payment/modals/editPayment', $data)->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect('payment-method');
        }
    }

    function update(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'Type' => 'required',
                'Value' => 'required',
            ]);

            $type = $request->Type;
            $value = $request->Value;

            $id = $request->ID;


            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->toArray()], 422); // Return validation errors with 422 status code
            } else {
                $data = [
                    'Payment_type' => $type,
                    'Value' => $value,
                    'ActiveF' => 1,
                    'Last_Date' => date('Y-m-d H-i-s'),
                    'Last_User' => 1,
                ];


                $process = PaymentModel::updatePayment($id, $data);
                if ($process) {
                    Session::flash('message', 'Payment Berhasil Diupdate');
                    Session::flash('alert', 'alert-success');
                    $msg = [
                        'success' => 'Payment Berhasil Diupdate'
                    ];
                } else {
                    Session::flash('message', 'Payment Gagal Diupdate');
                    Session::flash('alert', 'alert-danger');
                    $msg = [
                        'failed' => 'Payment Gagal Diupdate'
                    ];
                }

                return response()->json($msg);
            }
        } else {
            return redirect('payment-method');
        }
    }

    function deletePayment(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $payment = PaymentModel::findOrFail($id);
            $data = [
                'payment' => $payment
            ];

            $view = view('payment/modals/deletePayment', $data)->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect('payment-method');
        }
    }

    public function delete(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->ID;
            $process = PaymentModel::deletePayment($id);

            if ($process) {
                Session::flash('message', 'Data Payment Berhasil Dihapus');
                Session::flash('alert', 'alert-success');
                $msg = [
                    'success' => 'Data Payment Berhasil Dihapus'
                ];
            } else {
                Session::flash('message', 'Data Payment Gagal Dihapus');
                Session::flash('alert', 'alert-danger');
                $msg = [
                    'failed' => 'Data Payment Gagal Dihapus'
                ];
            }

            return response()->json($msg);
        } else {
            return redirect('payment-method');
        }
    }
}
