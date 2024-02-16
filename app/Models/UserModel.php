<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserModel extends Model
{
    protected $table = "user";
    protected $connection = "users";

    protected $guarded = ["ID"];
    use HasFactory;

    public static function getDataUser($uniqueid)
    {
        $sql = DB::connection('users')->table('user')
            ->select('*')
            ->where('Uniqueid', $uniqueid)
            ->get();
        return $sql;
    }
}
