@extends('layouts.app')

@section('title', 'Tambah Aset Sewaan — JAKOA ICT')

@section('content')
<div class="p-6 max-w-3xl mx-auto" x-data="assetForm()">
  <h1 class="text-2xl font-bold mb-4">Tambah Aset Sewaan</h1>
  <p class="text-sm text-slate-400 mb-6">Isi maklumat aset dengan lengkap. Ruang bertanda <span class="text-red-500">*</span> wajib diisi.</p>

  @if($errors->any())
    <div class="mb-4 text-sm text-red-700 bg-red-50 p-3 rounded">
      <ul class="list-disc ml-5">
        @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.assets.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6 space-y-5">
    @csrf

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <!-- Nama Aset -->
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Aset <span class="text-red-500">*</span></label>
        <input name="nama_aset" value="{{ old('nama_aset') }}" type="text" required
               class="w-full border border-slate-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sky-200">
        <p class="text-xs text-slate-400 mt-1">Contoh: Komputer Riba Dell Inspiron 15</p>
      </div>

      <!-- Kategori -->
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
        <input name="kategori" value="{{ old('kategori') }}" type="text"
               placeholder="Komputer / Pencetak / Projekor"
               class="w-full border border-slate-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sky-200">
      </div>

      <!-- Jenama -->
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Jenama</label>
        <input name="jenama" value="{{ old('jenama') }}" type="text"
               placeholder="Dell / HP / Epson"
               class="w-full border border-slate-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sky-200">
      </div>

      <!-- No Siri -->
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">No Siri / Serial Number</label>
        <input name="no_siri" value="{{ old('no_siri') }}" type="text"
               placeholder="S/N-XXXXXXXX"
               class="w-full border border-slate-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sky-200">
      </div>
    </div>

    <!-- Keterangan -->
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Keterangan</label>
      <textarea name="keterangan" rows="4" class="w-full border border-slate-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sky-200" placeholder="Maklumat tambahan, keadaan semasa, aksesori yang disertakan...">{{ old('keterangan') }}</textarea>
      <p class="text-xs text-slate-400 mt-1">Boleh letakkan nota, lokasi, atau nombor asset dalaman.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <!-- Bilangan -->
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Bilangan <span class="text-red-500">*</span></label>
        <input name="bilangan" value="{{ old('bilangan', 1) }}" type="number" min="1" required
               class="w-full border border-slate-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sky-200">
      </div>

      <!-- Status -->
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
        <select name="status" class="w-full border border-slate-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sky-200">
          <option value="Aktif" {{ old('status')=='Aktif' ? 'selected' : '' }}>Aktif</option>
          <option value="Dalam Penyelenggaraan" {{ old('status')=='Dalam Penyelenggaraan' ? 'selected' : '' }}>Dalam Penyelenggaraan</option>
          <option value="Tidak Aktif" {{ old('status')=='Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
          <option value="Dipinjam" {{ old('status')=='Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
        </select>
      </div>

      <!-- Reserved/placeholder for future -->
      <div class="hidden sm:block"></div>
    </div>

    <!-- Image upload with preview -->
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-2">Gambar Aset (pilihan)</label>

      <div class="border border-dashed border-slate-200 rounded-lg p-4 flex flex-col sm:flex-row gap-4 items-center">
        <div class="w-full sm:w-48 h-36 bg-slate-50 rounded flex items-center justify-center overflow-hidden">
          <template x-if="previewUrl">
            <img :src="previewUrl" alt="preview" class="object-contain w-full h-full"/>
          </template>
          <template x-if="!previewUrl">
            <div class="text-slate-400 text-center px-2">
              <svg class="mx-auto mb-2 h-8 w-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="1.5" d="M3 15a4 4 0 004 4h10a4 4 0 004-4V7a4 4 0 00-4-4H7a4 4 0 00-4 4v8z"/>
                <path stroke-width="1.5" d="M8 11l2 2 4-4 4 4"/>
              </svg>
              <div class="text-xs">Preview</div>
            </div>
          </template>
        </div>

        <div class="flex-1">
          <div class="flex items-center gap-3">
            <label class="cursor-pointer inline-flex items-center gap-2 px-3 py-2 bg-sky-600 text-white rounded hover:bg-sky-700">
              <input type="file" name="gambar" accept="image/*" @change="onFileChange" class="hidden">
              Muat Naik Gambar
            </label>

            <button type="button" x-show="previewUrl" @click="removeImage" class="px-3 py-2 border rounded text-sm">Buang</button>
          </div>

          <div class="mt-2 text-xs text-slate-500">
            Sokong format JPG, PNG. Saiz maksimum 5MB. Anda boleh tambah gambar untuk pengesahan fizikal aset.
          </div>

          <div class="mt-3 text-sm text-slate-600" x-text="fileName"></div>
        </div>
      </div>
    </div>

    <div class="flex justify-between items-center pt-3">
      <a href="{{ route('admin.assets.index') }}" class="text-slate-600 hover:underline">Batal</a>
      <div class="flex items-center gap-3">
        <button type="submit" class="bg-sky-600 text-white px-4 py-2 rounded hover:bg-sky-700">Simpan Aset</button>
      </div>
    </div>
  </form>
</div>

@push('scripts')
<script>
  function assetForm(){
    return {
      previewUrl: null,
      fileName: '',
      onFileChange(e){
        const file = e.target.files[0];
        if(!file) return this.removeImage();

        // client-side size limit check (5MB)
        if (file.size > 5 * 1024 * 1024) {
          alert('Fail terlalu besar. Maksimum 5MB.');
          e.target.value = '';
          return;
        }

        this.fileName = file.name;
        const reader = new FileReader();
        reader.onload = (ev) => { this.previewUrl = ev.target.result; };
        reader.readAsDataURL(file);
      },
      removeImage(){
        this.previewUrl = null;
        this.fileName = '';
        // also clear the file input (by replacing the form)
        const input = document.querySelector('input[type="file"][name="gambar"]');
        if(input) input.value = '';
      }
    }
  }
</script>
@endpush
@endsection
