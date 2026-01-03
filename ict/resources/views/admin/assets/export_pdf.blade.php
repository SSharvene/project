<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Laporan Aset Sewaan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f2f2f2; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Laporan Aset Sewaan</h2>
    <p>Tarikh: {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Aset</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Tarikh Daftar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assets as $a)
            <tr>
                <td>{{ $a->id }}</td>
                <td>{{ $a->nama_aset }}</td>
                <td>{{ $a->kategori }}</td>
                <td>{{ $a->status }}</td>
                <td>{{ optional($a->created_at)->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
