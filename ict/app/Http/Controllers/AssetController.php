<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 12);
        $viewMode = $request->input('view', 'card'); // card | list | photo

        $query = Asset::query();

        // simple search across title / brand / model / serial (adjust column names to your schema)
        if ($search = $request->input('q')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        // filter by category (if your asset has 'category' column)
        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        // optional status filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $assets = $query->orderBy('created_at','desc')->paginate($perPage)->withQueryString();

        // categories for filter dropdown
        $categories = Asset::select('category')->distinct()->pluck('category')->filter()->values();

        return view('assets.index', compact('assets','categories','viewMode'));
    }

    /**
     * Export current filtered list to CSV (server-side).
     */
    public function export(Request $request)
    {
        $fileName = 'aset_sewaan_export_'.date('Ymd_His').'.csv';

        $query = Asset::query();

        if ($search = $request->input('q')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }
        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $columns = ['id','title','category','brand','model','serial_number','status','created_at'];

        $response = new StreamedResponse(function() use ($query, $columns) {
            $handle = fopen('php://output', 'w');
            // header row
            fputcsv($handle, array_map(fn($c)=>ucfirst(str_replace('_',' ',$c)), $columns));

            $query->orderBy('created_at','desc')->chunk(200, function($rows) use ($handle, $columns) {
                foreach ($rows as $row) {
                    $line = [];
                    foreach ($columns as $col) {
                        $line[] = $row->$col;
                    }
                    fputcsv($handle, $line);
                }
            });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', "attachment; filename={$fileName}");

        return $response;
    }
}
