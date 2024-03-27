<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use Exception;
use Illuminate\Http\Request;
use App\Models\SubCategoryModel;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    function index()
    {
        $data = [
            'title' => 'Sub Category',
        ];
        return view("sub_category.index", $data);
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

            $tabledata = view('sub_category.tableData')->render();
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


            $subcategory = SubCategoryModel::getSubCategory($start, $length);
            $summary =  SubCategoryModel::summarySubCategory();

            $msg = [
                'draw' => intval($param['draw']),
                'recordsTotal' => $summary,
                'recordsFiltered' => $summary,
                'data' => $subcategory
            ];

            return response()->json($msg, 200);
        } else {
            return redirect('sub-category');
        }
    }

    function newSubCategory(Request $request)
    {
        if ($request->ajax()) {
            $data = [
                'category' => CategoryModel::where('ActiveF', '1')->get()
            ];

            $view = view('sub_category/modals/newSubCategory', $data)->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect('sub-category');
        }
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'category' => 'required',
                'sub_category' => 'required',
                'icon' => 'image|mimes:jpeg,jpg,png,webp|max:2048',
            ]);

            $category = $request->category;
            $sub_category = $request->sub_category;

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->toArray()], 422); // Return validation errors with 422 status code
            } else {

                if ($request->file('icon')) {
                    $image = $request->file('icon');
                    $filename = $image->storeAs('subcategory', $image->hashName());
                } else {
                    $filename = '';
                }


                $data = [
                    'ID_Category' => $category,
                    'Sub_Category' => $sub_category,
                    'Icon' => $filename,
                    'ActiveF' => 1,
                    'CDate' => date('Y-m-d H-i-s'),
                    'CUserID' => 1,
                ];


                $process = SubCategoryModel::saveSubCategory($data);

                if ($process) {
                    Session::flash('message', 'Sub Category Berhasil Dibuat');
                    Session::flash('alert', 'alert-success');
                    $msg = [
                        'success' => 'Sub Category Berhasil Dibuat'
                    ];
                } else {
                    Session::flash('message', 'Sub Category Gagal Dibuat');
                    Session::flash('alert', 'alert-danger');
                    $msg = [
                        'failed' => 'Sub Category Gagal Dibuat'
                    ];
                }


                return response()->json($msg);
            }
        } else {
            return redirect('sub-category');
        }
    }

    function editSubCategory(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;

            $subcategory = SubCategoryModel::findOrFail($id);
            $data = [
                'category' => CategoryModel::where('ActiveF', '1')->get(),
                'subcategory' => $subcategory,
            ];
            $view = view('sub_category/modals/editSubCategory', $data)->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect('sub-category');
        }
    }

    function update(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'category' => 'required',
                'sub_category' => 'required',
                'icon' => 'image|mimes:jpeg,jpg,png,webp|max:2048',
            ]);

            $category = $request->category;
            $sub_category = $request->sub_category;
            $activeF = $request->activeF;

            $id = $request->id;
            $post = SubCategoryModel::findOrFail($id);


            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->toArray()], 422); // Return validation errors with 422 status code
            } else {

                if ($request->hasFile('icon')) {
                    //upload new image
                    $image = $request->file('icon');
                    $filename = $image->storeAs('subcategory', $image->hashName());

                    //delete old image
                    if ($post->Icon <> '') {
                        Storage::delete($post->Icon);
                    }

                    //update post with new image
                    $data = [
                        'ID_Category' => $category,
                        'Sub_Category' => $sub_category,
                        'Icon' => $filename,
                        'ActiveF' => is_null($activeF) ? '0' : '1',
                        'Last_Date' => date('Y-m-d H-i-s'),
                        'Last_User' => 1,
                    ];
                } else {
                    $data = [
                        'ID_Category' => $category,
                        'Sub_Category' => $sub_category,
                        'ActiveF' => is_null($activeF) ? '0' : '1',
                        'Last_Date' => date('Y-m-d H-i-s'),
                        'Last_User' => 1,
                    ];
                }

                $process = SubCategoryModel::updateSubCategory($id, $data);

                if ($process) {
                    Session::flash('message', 'Sub Category Berhasil Diupdate');
                    Session::flash('alert', 'alert-success');
                    $msg = [
                        'success' => 'Sub Category Berhasil Diupdate'
                    ];
                } else {
                    Session::flash('message', 'Sub Category Gagal Diupdate');
                    Session::flash('alert', 'alert-danger');
                    $msg = [
                        'failed' => 'Sub Category Gagal Diupdate'
                    ];
                }

                return response()->json($msg);
            }
        } else {
            return redirect('category');
        }
    }

    function deleteSubCategory(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $subcategory = SubCategoryModel::getDataSubCategory($id);
            $data = [
                'subcategory' => $subcategory
            ];

            $view = view('sub_category/modals/deleteSubCategory', $data)->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect('sub-category');
        }
    }

    public function delete(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->ID;
            $post = SubCategoryModel::findOrFail($id);

            if ($post->Icon <> '') {
                Storage::delete($post->Icon);
            }

            $process = SubCategoryModel::deleteSubCategory($id);

            if ($process) {
                Session::flash('message', 'Data Sub Category Berhasil Dihapus');
                Session::flash('alert', 'alert-success');
                $msg = [
                    'success' => 'Data Sub Category Berhasil Dihapus'
                ];
            } else {
                Session::flash('message', 'Data Sub Category Gagal Dihapus');
                Session::flash('alert', 'alert-danger');
                $msg = [
                    'failed' => 'Data Sub Category Gagal Dihapus'
                ];
            }

            return response()->json($msg);
        } else {
            return redirect('sub-category');
        }
    }
}
