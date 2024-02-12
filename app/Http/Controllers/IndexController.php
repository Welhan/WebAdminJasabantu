<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    function index()
    {
        $data = [
            'title' => 'Dashboard',
        ];
        return view("dashboard", $data);
    }
}
