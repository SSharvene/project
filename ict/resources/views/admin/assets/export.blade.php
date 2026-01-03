@extends('layouts.app')

@section('title', 'Laporan Aset Sewaan — JAKOA ICT')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Laporan Aset Sewaan</h1>

        <div class="space-x-2">
            <a href="{{ route('admin.assets.export.csv') }}" class="bg-green-600 text-white px-4 py-2 rounded">Export CSV</a>
            <a href="{{ route('admin.assets.export.pdf') }}" class="bg-red-600 text-white px-4 py-2 rounded">Export PDF</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4 overflow-x-auto">
        <table class="min-w-full text-sm text-slate-700">
            <thead>
                <tr class="bg-slate-100 text-slate-600">
                    <th class="px-4 py-2 text-left">Nama Aset</th>
                    <th class="px-4 py-2 text-left">Kategori</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Tarikh Daftar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assets as $asset)
                    <tr class="border-b hover:bg-slate-50">
                        <td class="px-4 py-2">{{ $asset->nama_aset ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $asset->kategori ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $asset->status ?? '-' }}</td>
                        <td class="px-4 py-2">{{ optional($asset->created_at)->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
