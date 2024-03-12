<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ConfigModel extends Model
{
    protected $table = "list_value";

    protected $guarded = ["ID"];

    public static function getConfig($start, $length)
    {
        $sql = DB::table((new self())->getTable())
            ->select()->offset($start)->limit($length)
            ->get();
        return $sql;
    }

    public static function summaryConfig()
    {
        $sql = DB::table((new self())->getTable())
            ->count('ID');
        return $sql;
    }

    public static function saveConfig($data)
    {
        $sql = DB::table((new self())->getTable())
            ->insert($data);
        return $sql;
    }

    public static function updateConfig($id, $data)
    {
        $sql = DB::table((new self())->getTable())
            ->where('ID', $id)->update($data);
        return $sql;
    }

    public static function deleteConfig($id)
    {
        $sql = DB::table((new self())->getTable())
            ->where('ID', $id)->delete();
        return $sql;
    }
}
