<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SystabModel extends Model
{
    protected $table = "systab";

    protected $guarded = ["ID"];

    public static function getDataPrefix()
    {
        $sql = DB::table((new self())->getTable())
            ->select('Value')
            ->where('Config', 'Prefix')
            ->get();
        return $sql;
    }

    public static function getDataCounter()
    {
        $sql = DB::table((new self())->getTable())
            ->select('Value')
            ->where('Config', 'Counter')
            ->get();
        return $sql;
    }
}
