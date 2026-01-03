<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>{{ $single ? 'Aduan #' . ($aduans->first()->id ?? '') : 'Senarai Aduan' }}</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
    table { border-collapse: collapse; width: 100%; margin-top: 10px; }
    th, td { border: 1px solid #ddd; padding: 6px; text-align: left; vertical-align: top; }
    th { background: #f4f4f4; }
    .header { text-align: center; margin-bottom: 10px; }
    .meta { font-size: 11px; color: #555; }
    img.attachment { max-width: 120px; max-height: 120px; display:block; margin:6px 0; }
  </style>
</head>
<body>
  <div class="header">
    <h2>JAKOA ICT — Senarai Aduan</h2>
    <div class="meta">Dicetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</div>
  </div>

  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Tarikh & Masa</th>
        <th>Jenis</th>
        <th>Lokasi</th>
        <th>Penerangan</th>
        <th>Lampiran</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach($aduans as $a)
        <tr>
          <td>{{ $a->id }}</td>
          <td>{{ optional($a->tarikh_masa)->format('d/m/Y H:i') ?? $a->created_at->format('d/m/Y H:i') }}</td>
          <td>{{ $a->jenis_masalah }}</td>
          <td>{{ $a->lokasi }} {{ $a->lokasi_level ? ' - ' . $a->lokasi_level : '' }}</td>
          <td style="white-space:pre-wrap;">{{ $a->penerangan }}</td>
          <td>
            @if(!empty($a->attachments))
              @php $atts = is_array($a->attachments) ? $a->attachments : json_decode($a->attachments, true) ?? []; @endphp
              @foreach($atts as $att)
                @if($att)
                  <div>
                    {{-- If stored in storage/app/public/... use storage path --}}
                    <img class="attachment" src="{{ public_path('storage/'.$att) }}" alt="lampiran">
                  </div>
                @endif
              @endforeach
            @endif
          </td>
          <td>{{ $a->status }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
