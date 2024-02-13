<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MitraModel extends Model
{
    protected $table = "user";

    protected $guarded = ["ID"];

    public static function getMitra()
    {
        $sql = DB::table((new self())->getTable())
            ->select()
            ->get();
        return $sql;
    }

    public static function summaryMitra()
    {
        $sql = DB::table((new self())->getTable())
            ->count('ID');
        return $sql;
    }
}
