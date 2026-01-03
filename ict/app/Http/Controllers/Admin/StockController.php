<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stok;
use App\Models\StokRequest;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    // List all registered stock (admin view)
    public function index(Request $request)
    {
        $perPage = (int)$request->get('per_page', 15);
        $q = $request->get('q');

        $query = Stok::orderBy('created_at', 'desc');
        if ($q) {
            $query->where(function($q2) use ($q) {
                $q2->where('nama', 'like', "%{$q}%")
                   ->orWhere('kod', 'like', "%{$q}%")
                   ->orWhere('kategori', 'like', "%{$q}%");
            });
        }

        $stoks = $query->paginate($perPage)->withQueryString();
        return view('admin.stok.index', compact('stoks'));
    }

    // Show register stock form
    public function create()
    {
        return view('admin.stok.register');
    }

    // Store new stock
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'kod' => 'nullable|string|max:100|unique:stoks,kod',
            'kategori' => 'nullable|string|max:120',
            'kuantiti' => 'required|integer|min:0',
            'lokasi' => 'nullable|string|max:255',
            'nota' => 'nullable|string|max:1000',
        ]);

        $stok = Stok::create($data);

        return redirect()->route('admin.stok.index')->with('success', 'Stok berjaya didaftarkan.');
    }

    // Edit form
    public function edit(Stok $stok)
    {
        return view('admin.stok.register', compact('stok'));
    }

    // Update stock
    public function update(Request $request, Stok $stok)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'kod' => "nullable|string|max:100|unique:stoks,kod,{$stok->id}",
            'kategori' => 'nullable|string|max:120',
            'kuantiti' => 'required|integer|min:0',
            'lokasi' => 'nullable|string|max:255',
            'nota' => 'nullable|string|max:1000',
        ]);

        $stok->update($data);
        return redirect()->route('admin.stok.index')->with('success', 'Maklumat stok dikemaskini.');
    }

    // Delete stock
    public function destroy(Stok $stok)
    {
        $stok->delete();
        return redirect()->route('admin.stok.index')->with('success', 'Stok telah dipadam.');
    }

    // View incoming stock requests
    public function requests(Request $request)
    {
        $perPage = (int)$request->get('per_page', 20);
        $q = $request->get('q');

        $query = StokRequest::orderBy('created_at', 'desc');

        if ($q) {
            $query->where(function($qq) use ($q) {
                $qq->where('requester_name', 'like', "%{$q}%")
                   ->orWhere('jabatan', 'like', "%{$q}%")
                   ->orWhere('purpose', 'like', "%{$q}%");
            });
        }

        $requests = $query->paginate($perPage)->withQueryString();
        return view('admin.stok.requests', compact('requests'));
    }

    // Show single request
    public function showRequest(StokRequest $request)
    {
        return view('admin.stok.status', ['requestItem' => $request]);
    }

    // Update request status (approve/reject + optional assign quantity)
    public function updateRequestStatus(Request $request, StokRequest $requestItem)
    {
        $data = $request->validate([
            'status' => 'required|in:approved,rejected,pending',
            'admin_note' => 'nullable|string|max:1000',
            'approved_quantity' => 'nullable|integer|min:0',
        ]);

        $requestItem->status = $data['status'];
        $requestItem->admin_note = $data['admin_note'] ?? null;
        if (isset($data['approved_quantity'])) {
            $requestItem->approved_quantity = $data['approved_quantity'];
        }

        // if approved, reduce stok quantity if necessary
        if ($data['status'] === 'approved' && $requestItem->stok_id) {
            $stok = Stok::find($requestItem->stok_id);
            if ($stok && isset($data['approved_quantity'])) {
                $stok->kuantiti = max(0, $stok->kuantiti - (int)$data['approved_quantity']);
                $stok->save();
            }
        }

        $requestItem->handled_by = auth()->id();
        $requestItem->handled_at = now();
        $requestItem->save();

        // Optionally notify requester via email/notification here

        return redirect()->route('admin.stok.requests')->with('success', 'Status permohonan telah dikemaskini.');
    }

    // Optional status overview summary
    public function statusOverview()
    {
        $total = StokRequest::count();
        $pending = StokRequest::where('status','pending')->count();
        $approved = StokRequest::where('status','approved')->count();
        $rejected = StokRequest::where('status','rejected')->count();

        return view('admin.stok.status_overview', compact('total','pending','approved','rejected'));
    }
}
