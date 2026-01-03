<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stok;
use App\Models\StokRequest;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NewStokRequest;
use App\Models\User;

class StokRequestController extends Controller
{
    public function create()
    {
        $stoks = Stok::orderBy('nama')->get();
        $user = auth()->user();
        $prefill = [
            'requester_name' => $user->full_name ?? $user->name ?? ($user->nama ?? ''),
            'jabatan' => $user->bahagian ?? $user->jabatan ?? '',
        ];

        return view('stok_requests.create', compact('stoks','prefill'));
    }

    public function store(Request $request)
    {
        $rules = [
            'stok_id' => 'nullable|exists:stoks,id',
            'requester_name' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'purpose' => 'required|string|max:2000',
            'quantity' => 'required|integer|min:1',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ];

        $data = $request->validate($rules);

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('stok_requests/attachments', 'public');
            $data['attachment'] = $path;
        }

        $data['requester_id'] = auth()->id();
        $data['status'] = 'pending';

        $stokRequest = StokRequest::create($data);

        // Notify admins (users with role 'admin_ict' or method isAdminIct)
        $admins = User::where(function($q){
            $q->where('role','admin_ict');
        })->orWhere(function($q){
            // if you have role methods/stored differently, add conditions here
            $q->where('is_admin', 1);
        })->get();

        if ($admins->count() > 0) {
            foreach ($admins as $admin) {
                $admin->notify(new NewStokRequest($stokRequest));
            }
        }

        return redirect()->route('stok.request.index')->with('success', 'Permohonan stok berjaya dihantar.');
    }

    public function index(Request $request)
    {
        $perPage = (int)$request->get('per_page', 15);
        $requests = StokRequest::where('requester_id', auth()->id())
                   ->orderBy('created_at', 'desc')
                   ->paginate($perPage)
                   ->withQueryString();

        return view('stok_requests.index', compact('requests'));
    }

    public function show(StokRequest $request)
    {
        $user = auth()->user();
        $isAdmin = (method_exists($user, 'isAdminIct') && $user->isAdminIct()) || ($user->role ?? '') === 'admin_ict' || ($user->is_admin ?? false);

        if ($request->requester_id !== auth()->id() && !$isAdmin) {
            abort(403);
        }

        return view('stok_requests.show', ['requestItem' => $request]);
    }
}
