<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SubCategoryModel extends Model
{
    protected $table = "sub_category";

    protected $guarded = ["ID"];

    public static function getSubCategory($start, $length)
    {
        $sql = DB::table((new self())->getTable())
            ->select(DB::raw('sub_category.*,category.Category,ROW_NUMBER() OVER (ORDER BY sub_category.ID DESC) AS Number'))
            ->join('category', 'category.ID', '=', 'sub_category.ID_Category')
            ->where('category.ActiveF', 1)
            ->offset($start)->limit($length)
            ->get();
        return $sql;
    }

    public static function summarySubCategory()
    {
        $sql = DB::table((new self())->getTable())
            ->count('ID');
        return $sql;
    }

    public static function saveSubCategory($data)
    {
        $sql = DB::table((new self())->getTable())
            ->insert($data);
        return $sql;
    }

    public static function updateSubCategory($id, $data)
    {
        $sql = DB::table((new self())->getTable())
            ->where('ID', $id)->update($data);
        return $sql;
    }

    public static function deleteSubCategory($id)
    {
        $sql = DB::table((new self())->getTable())
            ->where('ID', $id)->delete();
        return $sql;
    }

    public static function getDataSubCategory($id)
    {
        $sql = DB::table((new self())->getTable())
            ->select(DB::raw('sub_category.*,category.Category'))
            ->join('category', 'category.ID', '=', 'sub_category.ID_Category')
            ->where('sub_category.ID', $id)
            ->get()[0];
        return $sql;
    }
}
