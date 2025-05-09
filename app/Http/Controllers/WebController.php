<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebController extends Controller
{
    public function loginview(){

        return view('web.login');
    }

    public function homeview(){

        return view('web.home');
    }
   

}
