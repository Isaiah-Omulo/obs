<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    //

     public function dashboardV2() {
        return view('pages/dashboard-v2');
    }

    public function loginV2() {
        return view('pages/login-v2');
    }

}
