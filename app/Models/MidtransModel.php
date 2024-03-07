<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MidtransModel extends Model
{
    protected $table = "midtrans";

    protected $guarded = ["ID"];

    public static function getMidtrans($start, $length)
    {
        $sql = DB::table((new self())->getTable())
            ->select()->offset($start)->limit($length)
            ->get();
        return $sql;
    }

    public static function summaryMidtrans()
    {
        $sql = DB::table((new self())->getTable())
            ->count('ID');
        return $sql;
    }

    public static function saveMidtrans($data)
    {
        $sql = DB::table((new self())->getTable())
            ->insert($data);
        return $sql;
    }

    public static function updateMidtrans($id, $data)
    {
        $sql = DB::table((new self())->getTable())
            ->where('ID', $id)->update($data);
        return $sql;
    }

    public static function deleteMidtrans($id)
    {
        $sql = DB::table((new self())->getTable())
            ->where('ID', $id)->delete();
        return $sql;
    }
}
