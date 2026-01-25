<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InstallController extends Controller
{
    public function index()
    {
        return view('install.index');
    }
}
