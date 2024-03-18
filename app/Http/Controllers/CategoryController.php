<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\CategoryModel;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    function index()
    {
        $data = [
            'title' => 'Category',
        ];
        return view("category.index", $data);
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

            $tabledata = view('category.tableData')->render();
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


            $category = CategoryModel::getCategory($start, $length);
            $summary =  CategoryModel::summaryCategory();

            $msg = [
                'draw' => intval($param['draw']),
                'recordsTotal' => $summary,
                'recordsFiltered' => $summary,
                'data' => $category
            ];

            return response()->json($msg, 200);
        } else {
            return redirect('category');
        }
    }

    function newCategory(Request $request)
    {
        if ($request->ajax()) {
            $view = view('category/modals/newCategory')->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect('category');
        }
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'category' => 'required|string|max:255',
                'icon' => 'image|mimes:jpeg,jpg,png|max:2048',
            ]);

            $category = $request->category;

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->toArray()], 422); // Return validation errors with 422 status code
            } else {

                if ($request->file()) {
                    $image = $request->file('icon');
                    $filename = $image->storeAs('images', $image->hashName());
                }


                $data = [
                    'Category' => $category,
                    'Icon' => $filename,
                    'ActiveF' => 1,
                    'CDate' => date('Y-m-d H-i-s'),
                    'CUserID' => 1,
                ];


                $process = CategoryModel::saveCategory($data);

                if ($process) {
                    Session::flash('message', 'Category Berhasil Dibuat');
                    Session::flash('alert', 'alert-success');
                    $msg = [
                        'success' => 'Category Berhasil Dibuat'
                    ];
                } else {
                    Session::flash('message', 'Category Gagal Dibuat');
                    Session::flash('alert', 'alert-danger');
                    $msg = [
                        'failed' => 'Category Gagal Dibuat'
                    ];
                }


                return response()->json($msg);
            }
        } else {
            return redirect('category');
        }
    }

    function editCategory(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;

            $category = CategoryModel::findOrFail($id);
            $data = [
                'category' => $category
            ];
            $view = view('category/modals/editCategory', $data)->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect('category');
        }
    }

    function update(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'category' => 'required|string|max:255',
                'icon' => 'image|mimes:jpeg,jpg,png|max:2048',
            ]);

            $category = $request->category;
            $activeF = $request->ActiveF;

            $id = $request->ID;
            $post = CategoryModel::findOrFail($id);


            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->toArray()], 422); // Return validation errors with 422 status code
            } else {

                if ($request->hasFile('image')) {

                    //upload new image
                    $image = $request->file('image');
                    $filename = $image->storeAs('images', $image->hashName());

                    //delete old image
                    Storage::delete($post->image);

                    //update post with new image
                    $data = [
                        'Category' => $category,
                        'Icon' => $filename,
                        'ActiveF' => is_null($activeF) ? '0' : '1',
                        'Last_Date' => date('Y-m-d H-i-s'),
                        'Last_User' => 1,
                    ];
                } else {
                    $data = [
                        'Category' => $category,
                        'ActiveF' => is_null($activeF) ? '0' : '1',
                        'Last_Date' => date('Y-m-d H-i-s'),
                        'Last_User' => 1,
                    ];
                }

                $process = CategoryModel::updateCategory($id, $data);
                if ($process) {
                    Session::flash('message', 'Category Berhasil Diupdate');
                    Session::flash('alert', 'alert-success');
                    $msg = [
                        'success' => 'Category Berhasil Diupdate'
                    ];
                } else {
                    Session::flash('message', 'Category Gagal Diupdate');
                    Session::flash('alert', 'alert-danger');
                    $msg = [
                        'failed' => 'Category Gagal Diupdate'
                    ];
                }

                return response()->json($msg);
            }
        } else {
            return redirect('category');
        }
    }

    function deleteCategory(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $category = CategoryModel::findOrFail($id);
            $data = [
                'category' => $category
            ];

            $view = view('category/modals/deleteCategory', $data)->render();
            return response()->json(['view' => $view], 200);
        } else {
            return redirect('category');
        }
    }

    public function delete(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->ID;
            $post = CategoryModel::findOrFail($id);
            Storage::delete($post->image);
            $process = CategoryModel::deleteCatenewCategory($id);

            if ($process) {
                Session::flash('message', 'Data CatenewCategory Berhasil Dihapus');
                Session::flash('alert', 'alert-success');
                $msg = [
                    'success' => 'Data CatenewCategory Berhasil Dihapus'
                ];
            } else {
                Session::flash('message', 'Data CatenewCategory Gagal Dihapus');
                Session::flash('alert', 'alert-danger');
                $msg = [
                    'failed' => 'Data CatenewCategory Gagal Dihapus'
                ];
            }

            return response()->json($msg);
        } else {
            return redirect('category');
        }
    }
}
