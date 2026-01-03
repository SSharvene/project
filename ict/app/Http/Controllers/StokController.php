<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StokController extends Controller
{
    // show list of stok items (placeholder)
    public function index(Request $request)
    {
        $user = Auth::user();

        // TODO: replace with real model query, e.g. \App\Models\Stok::paginate(...)
        $items = []; // placeholder; change to actual DB query when model exists

        return view('stok.index', [
            'items' => $items,
        ]);
    }

    // other resource methods (create, store, show, edit, update, destroy)
    public function create() { abort(404); }
    public function store(Request $r) { abort(404); }
    public function show($id) { abort(404); }
    public function edit($id) { abort(404); }
    public function update(Request $r,$id) { abort(404); }
    public function destroy($id) { abort(404); }
}
