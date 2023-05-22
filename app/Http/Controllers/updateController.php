<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class updateController extends Controller
{
    public function make()
    {
        return view('make', ["word" => 'make make! おまけのmake!']);
    }
}
