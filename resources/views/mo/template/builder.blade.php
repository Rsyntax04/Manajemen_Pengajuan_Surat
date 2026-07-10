@extends('layouts.mo')

@section('title', 'Upload Template Surat')

@section('content')

<div class="p-6 max-w-2xl">

    <h1 class="text-2xl font-bold mb-6">
        Upload Template Word per Jenis Surat
    </h1>

    <form action="{{ route('mo.template.upload') }}"
          method="POST"
          enctype="multipart/form-data"
          class="bg-white p-6 border rounded">

        @csrf

        <!-- JENIS SURAT -->
        <label class="block font-bold mb-2">Pilih Jenis Surat</label>
        <select name="jenis_surat_id" id="jenisSelect" class="border p-2 w-full">

            <option value="">-- pilih jenis surat --</option>
        
            @foreach($jenisSurat as $j)
                <option 
                    value="{{ $j->id }}"
                    data-file="{{ $j->template_file }}">
                    {{ $j->nama_jenis }}
                </option>
            @endforeach
        
        </select>
        <div id="templateInfo" class="mt-4 p-3 bg-green-50 border rounded hidden">

            <div class="font-bold text-green-700">
                Template Aktif
            </div>
        
            <div class="text-sm mt-1" id="templateFile">
                -
            </div>
        
        </div>
        <!-- FILE -->
        <label class="block font-bold mb-2">Upload Word</label>
        <input type="file" name="template_file" class="border p-2 w-full" required>
        <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">
            Upload & Convert
        </button>

    </form>
    @error('template_file')
        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
    @enderror
</div>

@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const select = document.getElementById('jenisSelect');
    const box = document.getElementById('templateInfo');
    const text = document.getElementById('templateFile');

    if (!select) return;

    select.addEventListener('change', function () {

        let selected = this.options[this.selectedIndex];
        let file = selected.getAttribute('data-file');

        if (file) {
            box.classList.remove('hidden');
            text.innerHTML = "📄 " + file;
        } else {
            box.classList.add('hidden');
            text.innerHTML = "-";
        }

        console.log("selected:", selected);
        console.log("file:", file);
    });

});

</script>
@endsection