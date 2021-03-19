<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::user()->status == "Inactive"){
            return view('user_public.password');
        }else if(Auth::user()->type == "Customer Relations Officer"){
            return view('user_cro.index');    
        }else if(Auth::user()->type == "Cashier"){
            return view('user_finance.index');
        }else if(Auth::user()->type == "Lab Analyst"){
            return view('user_analyst.index');
        }else{
            return view('user_admin.index');
        }
    }


}
