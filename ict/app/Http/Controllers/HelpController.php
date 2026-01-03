<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function index()
    {
        // Example: load a help page view
        return view('admin.help.index');
    }
}
