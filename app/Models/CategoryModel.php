<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CategoryModel extends Model
{
    protected $table = "category";

    protected $guarded = ["ID"];

    protected $fillable = [
        'Category', 'Icon', 'ActiveF', 'CDate', 'CUserID', 'Last_Date', 'Last_User'
    ];

    public static function getCategory($start, $length)
    {
        $sql = DB::table((new self())->getTable())
            ->select()->offset($start)->limit($length)
            ->get();
        return $sql;
    }

    public static function summaryCategory()
    {
        $sql = DB::table((new self())->getTable())
            ->count('ID');
        return $sql;
    }

    public static function saveCategory($data)
    {
        $sql = DB::table((new self())->getTable())
            ->insert($data);
        return $sql;
    }

    public static function updateCategory($id, $data)
    {
        $sql = DB::table((new self())->getTable())
            ->where('ID', $id)->update($data);
        return $sql;
    }

    public static function deleteCategory($id)
    {
        $sql = DB::table((new self())->getTable())
            ->where('ID', $id)->delete();
        return $sql;
    }
}
