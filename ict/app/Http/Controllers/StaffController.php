<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffController extends Controller
{
    // shows staff dashboard
    public function index()
    {
        // return a view; create resources/views/dashboard/staff.blade.php next
        return view('dashboard.staff');
    $user = auth()->user();
    $query = $user->aduans()->latest();

    if($req->filled('q')) {
        $query->where('tajuk','like','%'.$req->q.'%')->orWhere('penerangan','like','%'.$req->q.'%');
    }
    if($req->filled('status')) {
        $query->where('status', $req->status);
    }

    $perPage = (int) $req->get('per_page', 10);
    $aduans = $query->paginate($perPage);

    return view('aduan.staff_index', [
        'aduans' => $aduans,
        'unreadCount' => $user->aduans()->where('is_read', false)->count(),
        // chart data etc if needed
    ]);
}

    }