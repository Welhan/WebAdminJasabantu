<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MitraModel extends Model
{
    protected $table = "user";

    protected $guarded = ["ID"];

    public static function getMitra($start, $length)
    {
        $sql = DB::table((new self())->getTable())
            ->select()->offset($start)->limit($length)
            ->get();
        return $sql;
    }

    public static function summaryMitra()
    {
        $sql = DB::table((new self())->getTable())
            ->count('ID');
        return $sql;
    }

    public static function saveMitra($data)
    {
        $sql = DB::table((new self())->getTable())
            ->insert($data);
        return $sql;
    }

    public static function updateMitra($uniqueid, $data)
    {
        $sql = DB::table((new self())->getTable())
            ->where('UniqueID', $uniqueid)->update($data);
        return $sql;
    }

    public static function deleteMitra($uniqueid)
    {
        $sql = DB::table((new self())->getTable())
            ->where('UniqueID', $uniqueid)->delete();
        return $sql;
    }
}
