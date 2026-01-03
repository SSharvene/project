<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aduan;
use Illuminate\Support\Facades\Auth;
use App\Models\AduanKategori;
use Illuminate\Support\Str;
use App\Http\Requests\StoreAduanRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;



class AduanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // base query: only this user's aduan
        $query = Aduan::where('user_id', $user->id)->latest();

        if($request->filled('q')){
            $q = $request->q;
            $query->where(function($b) use ($q){
                $b->where('tajuk','like',"%{$q}%")
                  ->orWhere('penerangan','like',"%{$q}%");
            });
        }

        if($request->filled('status')){
            $query->where('status', $request->status);
        }

        $perPage = (int) $request->get('per_page', 10);
        $aduans = $query->paginate($perPage);

        $unreadCount = Aduan::where('user_id', $user->id)->where('is_read', false)->count();

        return view('aduan.staff_index', compact('aduans','unreadCount'));
    }

    // show used by AJAX modal
    public function show(Aduan $aduan, Request $request)
    {
        // only allow owner or authorized staff to fetch JSON
        if($request->ajax()){
            $attachments = $aduan->attachments()->get()->map(fn($a)=>[
                'name' => $a->filename,
                'url' => $a->url,
            ]);
            return response()->json([
                'id'=>$aduan->id,
                'tajuk'=>$aduan->tajuk,
                'penerangan'=>$aduan->penerangan,
                'lokasi'=>$aduan->lokasi,
                'jenis_peranti'=>$aduan->jenis_peranti,
                'is_read'=>$aduan->is_read,
                'created_at'=>$aduan->created_at->format('d M Y H:i'),
                'attachments'=>$attachments,
            ]);
        }
        return view('aduan.show', compact('aduan'));
    }

    public function markRead(Aduan $aduan)
    {
        $this->authorize('update', $aduan); // optional, ensure owner
        $aduan->is_read = true;
        $aduan->save();

        return response()->json(['success'=>true]);
    }



    public function create()
    {
        // Problem types - adjust as needed or fetch from DB
        $jenisMasalah = [
            'Perkakasan', 'Perisian', 'Rangkaian', 'Akses', 'Lain-lain'
        ];

        return view('aduan.create', compact('jenisMasalah'));
    }

    public function store(StoreAduanRequest $request) // or (Request $request) if validating inline
{
    // If you used StoreAduanRequest the data is already validated via $request->validated()
    $data = $request->only([
        'nama_penuh','jawatan','bahagian','emel','telefon',
        'tarikh_masa','jenis_masalah','jenis_peranti','jenama_model','nombor_siri_aset',
        'lokasi','lokasi_level','penerangan'
    ]);

    $data['user_id'] = $request->user()->id;
    $data['status']  = 'Menunggu';

    DB::beginTransaction();
    try {
        $aduan = \App\Models\Aduan::create($data);

        if ($request->hasFile('attachments')) {
            $paths = [];
            foreach ($request->file('attachments') as $file) {
                if (!$file->isValid()) continue;

                $filename = Str::random(12) . '_' . time() . '.' . $file->getClientOriginalExtension();
                // storeAs writes to storage/app/public/aduan_images/...
                $path = $file->storeAs('public/aduan_images', $filename);
                // Save relative path without 'public/' so you can use asset('storage/...')
                $paths[] = str_replace('public/', '', $path);
            }

            if (!empty($paths)) {
                // If attachments column is JSON or text, save as array or json depending on model cast
                $aduan->attachments = $paths;
                $aduan->save();
            }
        }

        DB::commit();

        return redirect()->route('aduan.index')->with('success', 'Aduan berjaya dihantar.');
    } catch (\Throwable $e) {
        DB::rollBack();
        \Log::error('Aduan store failed: '.$e->getMessage());
        return back()->withInput()->withErrors(['error' => 'Gagal menghantar aduan. Sila cuba lagi.']);
    }
}

    // List categories (with search + pagination)
public function kategoriIndex(Request $request)
{
    $q = $request->get('q');
    $perPage = (int) $request->get('per_page', 15);

    $query = AduanKategori::orderBy('created_at', 'desc');

    if ($q) {
        $query->where('name', 'like', "%{$q}%");
    }

    $kategoris = $query->paginate($perPage)->appends($request->query());

    return view('aduan.kategori.index', compact('kategoris', 'q'));
}

// Show category create form
public function kategoriCreate()
{
    return view('aduan.kategori.create');
}

// Store new category
public function kategoriStore(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:190|unique:aduan_kategoris,name',
        'description' => 'nullable|string|max:2000',
        'is_active' => 'nullable|boolean',
    ]);

    $data['slug'] = Str::slug($data['name']);
    $data['created_by'] = $request->user()->id;
    $data['is_active'] = $request->has('is_active') ? (bool)$request->is_active : true;

    AduanKategori::create($data);

    return redirect()->route('aduan.kategori')->with('success', 'Kategori aduan berjaya ditambah.');
}

// Show category edit form
public function kategoriEdit(AduanKategori $kategori)
{
    return view('aduan.kategori.edit', compact('kategori'));
}

// Update category
public function kategoriUpdate(Request $request, AduanKategori $kategori)
{
    $data = $request->validate([
        'name' => 'required|string|max:190|unique:aduan_kategoris,name,' . $kategori->id,
        'description' => 'nullable|string|max:2000',
        'is_active' => 'nullable|boolean',
    ]);

    $data['slug'] = Str::slug($data['name']);
    $data['is_active'] = $request->has('is_active') ? (bool)$request->is_active : false;

    $kategori->update($data);

    return redirect()->route('aduan.kategori')->with('success', 'Kategori aduan dikemaskini.');
}

// Delete category
public function kategoriDestroy(AduanKategori $kategori)
{
    $kategori->delete();
    return redirect()->route('aduan.kategori')->with('success', 'Kategori aduan dipadam.');
}

public function pdf(Request $request)
{
    // Decide what to export. By default export current user's aduans (staff view).
    $user = $request->user();

    // If you want to allow admin to export all, you can check roles here and override $user.
    // E.g. if ($user->isAdmin()) { $aduans = Aduan::orderBy('created_at','desc')->get(); }

    // Optional: export a single aduan when ?id=123 provided
    if ($request->has('id')) {
        $aduan = \App\Models\Aduan::findOrFail($request->get('id'));
        $data = ['aduans' => collect([$aduan]), 'single' => true];
        $filename = 'aduan-'.$aduan->id.'.pdf';
    } else {
        // default: current user's aduans
        $aduans = \App\Models\Aduan::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $data = ['aduans' => $aduans, 'single' => false];
        $filename = 'aduan-list-'.$user->id.'-'.now()->format('YmdHis').'.pdf';
    }

    // If barryvdh/laravel-dompdf is available use it; otherwise render the view HTML
    if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('aduan.pdf', $data);
        // optional paper/orientation
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download($filename);
    }

    // fallback: return HTML view (useful for debugging or if package not installed)
    return view('aduan.pdf', $data);
}





    // pdf() omitted — add if you have pdf generator
}
