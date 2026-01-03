<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HrController extends Controller
{
    // Show HR dashboard
    public function index()
    {
        // Return a view for the HR dashboard
        return view('dashboard.hr'); // create this view next
    }
}
