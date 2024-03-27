<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuthModel extends Model
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
}
