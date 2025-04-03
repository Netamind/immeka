<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OperationsController extends Controller
{
   public function operationsdashboard(){
   
    return view('operations.dashboard');

   }
}
