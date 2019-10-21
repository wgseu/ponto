<?php

namespace App\Http\Controllers;

class IndexController extends Controller
{
    public function any()
    {
        return view('index');
    }
}
