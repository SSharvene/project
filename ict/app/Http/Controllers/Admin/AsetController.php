<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PDF; // if using barryvdh/laravel-dompdf

class AsetController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int)$request->get('per_page', 10);
        $assets = Asset::orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        return view('admin.assets.index', compact('assets'));
    }

    public function create()
    {
        return view('admin.assets.create');
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'nama_aset' => 'required|string|max:255',
        'kategori' => 'nullable|string|max:120',
        'jenama' => 'nullable|string|max:120',
        'no_siri' => 'nullable|string|max:255',
        'keterangan' => 'nullable|string|max:2000',
        'bilangan' => 'required|integer|min:1',
        'status' => 'required|string|max:60',
        'gambar' => 'nullable|image|max:5120', // 5MB
    ]);

    if ($request->hasFile('gambar')) {
        $path = $request->file('gambar')->store('assets/images', 'public');
        $data['gambar'] = $path;
    }

    $asset = \App\Models\Asset::create($data);

    return redirect()->route('admin.assets.index')->with('success', 'Aset berjaya ditambah.');
}


    public function edit(Asset $asset)
    {
        return view('admin.assets.edit', compact('asset'));
    }

    public function update(Request $request, Asset $asset)
    {
        $data = $request->validate([
        'nama_aset' => 'required|string|max:255',
        'kategori' => 'nullable|string|max:120',
        'jenama' => 'nullable|string|max:120',
        'no_siri' => 'nullable|string|max:255',
        'keterangan' => 'nullable|string|max:2000',
        'bilangan' => 'required|integer|min:1',
        'status' => 'required|string|max:60',
        'gambar' => 'nullable|image|max:5120', // 5MB
    ]);

    if ($request->hasFile('gambar')) {
        $path = $request->file('gambar')->store('assets/images', 'public');
        $data['gambar'] = $path;
    }

        $asset->update($data);

        return redirect()->route('admin.assets.index')->with('success', 'Maklumat aset telah dikemaskini.');
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();

        return redirect()->route('admin.assets.index')->with('success', 'Aset telah dipadam.');
    }

    // Human-friendly export page showing buttons (replaces previous export view)
    public function exportIndex()
    {
        $assets = Asset::orderBy('created_at', 'desc')->get();
        return view('admin.assets.export', compact('assets'));
    }

    // CSV export (streaming)
    public function exportCsv()
    {
        $fileName = 'aset_sewaan_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $columns = ['ID', 'Nama Aset', 'Kategori', 'Status', 'Tarikh Daftar', 'Nota'];

        $callback = function() use ($columns) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);

            Asset::orderBy('created_at', 'desc')->chunk(200, function($assets) use ($handle) {
                foreach ($assets as $a) {
                    fputcsv($handle, [
                        $a->id,
                        $a->nama_aset,
                        $a->kategori,
                        $a->status,
                        optional($a->created_at)->format('Y-m-d H:i:s'),
                        $a->nota,
                    ]);
                }
            });

            fclose($handle);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    // PDF export using barryvdh/laravel-dompdf (optional)
    public function exportPdf()
    {
        $assets = Asset::orderBy('created_at', 'desc')->get();
        // view 'admin.assets.export_pdf' must exist
        $pdf = PDF::loadView('admin.assets.export_pdf', compact('assets'))->setPaper('a4', 'landscape');

        $filename = 'aset_sewaan_' . now()->format('Ymd_His') . '.pdf';
        return $pdf->download($filename);
    }
}
