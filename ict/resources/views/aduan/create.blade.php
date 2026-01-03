@extends('layouts.app-blue')

@section('title', 'Buat Aduan')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">
  <h1 class="text-2xl font-semibold mb-4">Buat Aduan ICT</h1>

  @if ($errors->any())
    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded">
      <ul class="list-disc pl-5">
        @foreach ($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('aduan.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- Auto-filled complainant info --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block font-medium">Nama Penuh</label>
        <input type="text" name="nama_penuh" value="{{ old('nama_penuh', auth()->user()->full_name ?? auth()->user()->name ?? '') }}" class="w-full border p-2 rounded" required>
      </div>

      <div>
        <label class="block font-medium">Jawatan</label>
        <input type="text" name="jawatan" value="{{ old('jawatan', auth()->user()->jawatan ?? '') }}" class="w-full border p-2 rounded">
      </div>

      <div>
        <label class="block font-medium">Bahagian / Unit / Negeri</label>
        <input type="text" name="bahagian" value="{{ old('bahagian', auth()->user()->bahagian ?? '') }}" class="w-full border p-2 rounded">
      </div>

      <div>
        <label class="block font-medium">Emel Rasmi</label>
        <input type="email" name="emel" value="{{ old('emel', auth()->user()->email ?? '') }}" class="w-full border p-2 rounded">
      </div>

      <div>
        <label class="block font-medium">Nombor Telefon</label>
        <input type="text" name="telefon" value="{{ old('telefon', auth()->user()->phone ?? '') }}" class="w-full border p-2 rounded">
      </div>

      <div>
        <label class="block font-medium">Tarikh & Masa Aduan</label>
        <input type="datetime-local" name="tarikh_masa" value="{{ old('tarikh_masa') ?? \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" class="w-full border p-2 rounded" required>
      </div>
    </div>

    <hr class="my-4">

    {{-- Problem & device --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block font-medium">Jenis Masalah / Aduan</label>
        <select name="jenis_masalah" class="w-full border p-2 rounded" required>
          <option value="">-- Pilih --</option>
          @foreach($jenisMasalah as $jm)
            <option value="{{ $jm }}" {{ old('jenis_masalah') == $jm ? 'selected' : '' }}>{{ $jm }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block font-medium">Jenis Peranti</label>
        <input type="text" name="jenis_peranti" value="{{ old('jenis_peranti') }}" class="w-full border p-2 rounded">
      </div>

      <div>
        <label class="block font-medium">Jenama & Model</label>
        <input type="text" name="jenama_model" value="{{ old('jenama_model') }}" class="w-full border p-2 rounded">
      </div>

      <div>
        <label class="block font-medium">Nombor Siri Aset</label>
        <input type="text" name="nombor_siri_aset" value="{{ old('nombor_siri_aset') }}" class="w-full border p-2 rounded">
      </div>
    </div>

    <hr class="my-4">

    {{-- Location --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="md:col-span-2">
        <label class="block font-medium">Lokasi Peranti (Bangunan / Bilik / Cawangan)</label>
        <input type="text" name="lokasi" value="{{ old('lokasi') }}" class="w-full border p-2 rounded" placeholder="Contoh: Bangunan A, Bilik 203">
      </div>

      <div>
        <label class="block font-medium">Level</label>
        <select name="lokasi_level" class="w-full border p-2 rounded">
          <option value="">-- Pilih Level (jika berkaitan) --</option>
          <option value="Level 8" {{ old('lokasi_level') == 'Level 8' ? 'selected' : '' }}>Level 8</option>
          <option value="Level 9" {{ old('lokasi_level') == 'Level 9' ? 'selected' : '' }}>Level 9</option>
          <option value="Level 10" {{ old('lokasi_level') == 'Level 10' ? 'selected' : '' }}>Level 10</option>
        </select>
      </div>
    </div>

    <hr class="my-4">

    {{-- Description and attachments --}}
    <div>
      <label class="block font-medium">Penerangan Ringkas Masalah</label>
      <textarea name="penerangan" rows="6" class="w-full border p-2 rounded">{{ old('penerangan') }}</textarea>
    </div>

    <div class="mt-4">
      <label class="block font-medium">Lampiran (Gambar / Screenshot) — boleh pilih lebih 1</label>
      <input type="file" name="attachments[]" accept="image/*" multiple class="w-full">
      <p class="text-sm text-gray-500 mt-1">Maksima 5MB setiap fail. Format diterima: jpg, png, gif.</p>
    </div>

    <div class="mt-6 flex items-center gap-3">
      <button type="submit" class="px-4 py-2 bg-sky-600 text-white rounded">Hantar Aduan</button>
      <a href="{{ route('aduan.index') ?? url('/dashboard') }}" class="px-4 py-2 border rounded">Batal</a>
    </div>
  </form>
</div>
@endsection
