<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\BannerModel;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    function index()
    {
        $data = [
            'title' => 'Banner',
        ];
        return view("banner.index", $data);
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

            $tabledata = view('banner.tableData')->render();
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


            $banner = BannerModel::getBanner($start, $length);
            $summary =  BannerModel::summaryBanner();

            $msg = [
                'draw' => intval($param['draw']),
                'recordsTotal' => $summary,
                'recordsFiltered' => $summary,
                'data' => $banner,
            ];

            return response()->json($msg, 200);
        } else {
            return redirect('banner');
        }
    }

    function newBanner(Request $request)
    {
        if ($request->ajax()) {
            $view = view('banner/modals/newBanner')->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect('banner');
        }
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
            ]);

            $name = $request->name;
            $showF = $request->showF;

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->toArray()], 422); // Return validation errors with 422 status code
            } else {

                if ($request->file('image')) {
                    $image = $request->file('image');
                    $filename = $image->storeAs('banner', $image->hashName());
                }


                $data = [
                    'Name' => $name,
                    'Image' => $filename,
                    'ShowF' => is_null($showF) ? '0' : '1',
                    'CDate' => date('Y-m-d H-i-s'),
                    'CUserID' => 1,
                ];


                $process = BannerModel::saveBanner($data);

                if ($process) {
                    Session::flash('message', 'Banner Berhasil Dibuat');
                    Session::flash('alert', 'alert-success');
                    $msg = [
                        'success' => 'Banner Berhasil Dibuat'
                    ];
                } else {
                    Session::flash('message', 'Banner Gagal Dibuat');
                    Session::flash('alert', 'alert-danger');
                    $msg = [
                        'failed' => 'Banner Gagal Dibuat'
                    ];
                }


                return response()->json($msg);
            }
        } else {
            return redirect('banner');
        }
    }

    function editBanner(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;

            $banner = BannerModel::findOrFail($id);
            $data = [
                'banner' => $banner
            ];
            $view = view('banner/modals/editBanner', $data)->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect('banner');
        }
    }

    function update(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'image' => 'image|mimes:jpeg,jpg,png,webp|max:2048',
            ]);

            $name = $request->name;
            $showF = $request->showF;

            $id = $request->id;
            $post = BannerModel::findOrFail($id);


            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->toArray()], 422); // Return validation errors with 422 status code
            } else {

                if ($request->hasFile('icon')) {
                    //upload new image
                    $image = $request->file('icon');
                    $filename = $image->storeAs('images', $image->hashName());

                    //delete old image
                    Storage::delete($post->Icon);

                    //update post with new image
                    $data = [
                        'Name' => $name,
                        'Image' => $filename,
                        'ShowF' => is_null($showF) ? '0' : '1',
                        'Last_Date' => date('Y-m-d H-i-s'),
                        'Last_User' => 1,
                    ];
                } else {
                    $data = [
                        'Name' => $name,
                        'ShowF' => is_null($showF) ? '0' : '1',
                        'Last_Date' => date('Y-m-d H-i-s'),
                        'Last_User' => 1,
                    ];
                }

                $process = BannerModel::updateBanner($id, $data);

                if ($process) {
                    Session::flash('message', 'Banner Berhasil Diupdate');
                    Session::flash('alert', 'alert-success');
                    $msg = [
                        'success' => 'Banner Berhasil Diupdate'
                    ];
                } else {
                    Session::flash('message', 'Banner Gagal Diupdate');
                    Session::flash('alert', 'alert-danger');
                    $msg = [
                        'failed' => 'Banner Gagal Diupdate'
                    ];
                }

                return response()->json($msg);
            }
        } else {
            return redirect('banner');
        }
    }

    function deleteBanner(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $banner = BannerModel::findOrFail($id);
            $data = [
                'banner' => $banner
            ];

            $view = view('banner/modals/deleteBanner', $data)->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect('banner');
        }
    }

    public function delete(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $post = BannerModel::findOrFail($id);
            Storage::delete($post->Image);
            $process = BannerModel::deleteBanner($id);

            if ($process) {
                Session::flash('message', 'Data Banner Berhasil Dihapus');
                Session::flash('alert', 'alert-success');
                $msg = [
                    'success' => 'Data Banner Berhasil Dihapus'
                ];
            } else {
                Session::flash('message', 'Data Banner Gagal Dihapus');
                Session::flash('alert', 'alert-danger');
                $msg = [
                    'failed' => 'Data Banner Gagal Dihapus'
                ];
            }

            return response()->json($msg);
        } else {
            return redirect('banner');
        }
    }
}
