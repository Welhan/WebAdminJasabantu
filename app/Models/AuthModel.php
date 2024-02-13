<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
