<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class AboutusController extends Controller
{
    public function aboutus(){
        return view('user/aboutus');
    }
    
}
