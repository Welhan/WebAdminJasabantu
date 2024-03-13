<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentModel extends Model
{
    protected $table = "payment_method";

    protected $guarded = ["ID"];

    public static function getPayment($start, $length)
    {
        $sql = DB::table((new self())->getTable())
            ->select()->offset($start)->limit($length)
            ->get();
        return $sql;
    }

    public static function summaryPayment()
    {
        $sql = DB::table((new self())->getTable())
            ->count('ID');
        return $sql;
    }

    public static function savePayment($data)
    {
        $sql = DB::table((new self())->getTable())
            ->insert($data);
        return $sql;
    }

    public static function updatePayment($id, $data)
    {
        $sql = DB::table((new self())->getTable())
            ->where('ID', $id)->update($data);
        return $sql;
    }

    public static function deletePayment($id)
    {
        $sql = DB::table((new self())->getTable())
            ->where('ID', $id)->delete();
        return $sql;
    }
}
