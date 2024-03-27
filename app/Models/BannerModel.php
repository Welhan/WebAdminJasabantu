<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BannerModel extends Model
{
    protected $table = "banner";

    protected $guarded = ["ID"];

    public static function getBanner($start, $length)
    {
        $sql = DB::table((new self())->getTable())
            ->select(DB::raw('*,ROW_NUMBER() OVER (ORDER BY ID DESC) AS Number'))
            ->offset($start)->limit($length)
            ->get();
        return $sql;
    }

    public static function summaryBanner()
    {
        $sql = DB::table((new self())->getTable())
            ->count('ID');
        return $sql;
    }

    public static function saveBanner($data)
    {
        $sql = DB::table((new self())->getTable())
            ->insert($data);
        return $sql;
    }

    public static function updateBanner($id, $data)
    {
        $sql = DB::table((new self())->getTable())
            ->where('ID', $id)->update($data);
        return $sql;
    }

    public static function deleteBanner($id)
    {
        $sql = DB::table((new self())->getTable())
            ->where('ID', $id)->delete();
        return $sql;
    }
}
