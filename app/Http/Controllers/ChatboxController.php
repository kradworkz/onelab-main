<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatboxController extends Controller
{
    public function index(){
        return view('chatbox');
    }
}
