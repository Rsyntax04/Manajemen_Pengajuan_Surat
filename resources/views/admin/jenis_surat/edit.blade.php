@extends('layouts.app')


@section('title', 'Edit Jenis Surat')


@section('page-title', 'Edit Jenis Surat')



@section('content')



    <div class="bg-white rounded-2xl shadow-md p-6">



        <form method="POST" action="{{ route('jenis-surat.update', $jenisSurat->id) }}" enctype="multipart/form-data">



            @csrf

            @method('PUT')





            <h2 class="text-2xl font-bold mb-5">

                Edit Jenis Surat

            </h2>






            <div class="grid grid-cols-2 gap-5">



                <div>


                    <label class="font-semibold">

                        Nama Surat

                    </label>


                    <input name="nama_jenis" value="{{ $jenisSurat->nama_jenis }}" class="w-full border rounded-xl p-3"
                        required>

                    @error('nama_jenis')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror

                </div>






                <div>


                    <label class="font-semibold">

                        Kode Surat

                    </label>



                    <input name="kode_surat" value="{{ $jenisSurat->kode_surat }}" class="w-full border rounded-xl p-3"
                        required>

                    @error('kode_surat')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror

                </div>



            </div>








            <div class="mt-5">


                <label class="font-semibold">

                    Template Surat (.docx)

                </label>




                @if ($jenisSurat->template_file)
                    <div class="mb-2">

                        <a href="{{ asset('storage/' . $jenisSurat->template_file) }}" target="_blank"
                            class="text-blue-600">


                            Lihat Template Lama

                        </a>

                    </div>
                @endif





                <input type="file" name="template_file" accept=".doc,.docx" class="w-full border rounded-xl p-3">

                @error('template_file')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror

            </div>

            <h3 class="font-bold text-lg mt-8 mb-4 border-b pb-2">Informasi Penandatangan</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                <div>
                    <label class="font-semibold text-sm text-gray-700">Nama Penandatangan</label>
                    <input type="text" name="penandatangan_nama" value="{{ $jenisSurat->penandatangan_nama }}" class="w-full border rounded-xl p-3 mt-1" placeholder="Contoh: Dr. Ir. Budi Santoso, M.T.">
                    @error('penandatangan_nama')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="font-semibold text-sm text-gray-700">NIP / NIK</label>
                    <input type="text" name="penandatangan_nip" value="{{ $jenisSurat->penandatangan_nip }}" class="w-full border rounded-xl p-3 mt-1" placeholder="Contoh: 198001012005011001">
                    @error('penandatangan_nip')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="font-semibold text-sm text-gray-700">Jabatan</label>
                    <input type="text" name="penandatangan_jabatan" value="{{ $jenisSurat->penandatangan_jabatan }}" class="w-full border rounded-xl p-3 mt-1" placeholder="Contoh: Dekan / Ketua Program Studi">
                    @error('penandatangan_jabatan')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="my-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg">
                    Field Form
                </h3>
                <button type="button" onclick="addField()" class="bg-gray-600 text-white px-4 py-2 rounded-lg">
                    + Tambah Field
                </button>
            </div>
            <div id="fields">
                @foreach ($jenisSurat->fields as $i => $field)
                    <div class="field-item bg-gray-50 border rounded-xl p-4 mb-4">
                        <div class="grid grid-cols-5 gap-3 items-center">
                            <input name="fields[{{ $i }}][field_name]" value="{{ $field->field_name }}"
                                class="border rounded-lg p-2" placeholder="Nama Field">
                            <select name="fields[{{ $i }}][field_type]" class="border rounded-lg p-2">
                                <option value="text" {{ $field->field_type == 'text' ? 'selected' : '' }}>
                                    Text
                                </option>
                                <option value="date" {{ $field->field_type == 'date' ? 'selected' : '' }}>
                                    Date
                                </option>
                                <option value="number" {{ $field->field_type == 'number' ? 'selected' : '' }}>
                                    Number
                                </option>
                                <option value="file" {{ $field->field_type == 'file' ? 'selected' : '' }}>
                                    File
                                </option>
                            </select>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="fields[{{ $i }}][is_required]" value="1"
                                    {{ $field->is_required ? 'checked' : '' }}>
                                Required
                            </label>
                            <input type="hidden" name="fields[{{ $i }}][urutan]" value="{{ $field->urutan }}">
                            @if ($i == 0)
                                <div class="text-gray-400 text-sm text-center">
                                    Default
                                </div>
                            @else
                                <button type="button" onclick="removeField(this)"
                                    class="bg-red-600 text-white px-3 py-2 rounded-lg">
                                    ✕
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg">
                Update
            </button>







        </form>



    </div>



@endsection







@section('scripts')



    <script>
        let index = {{ $jenisSurat->fields->count() }};





        function addField() {



            let container =
                document.getElementById('fields');




            let html = `


<div class="field-item bg-gray-50 border rounded-xl p-4 mb-4">


<div class="grid grid-cols-5 gap-3 items-center">





<input

name="fields[${index}][field_name]"

placeholder="Nama Field"

class="border rounded-lg p-2">






<select

name="fields[${index}][field_type]"

class="border rounded-lg p-2">


<option value="text">

Text

</option>


<option value="date">

Date

</option>


<option value="number">

Number

</option>


<option value="file">

File

</option>


</select>







<label class="flex items-center gap-2">


<input

type="checkbox"

name="fields[${index}][is_required]"

value="1">


Required


</label>







<input

type="hidden"

name="fields[${index}][urutan]"

value="${index+1}">







<button

type="button"

onclick="removeField(this)"

class="bg-red-600 text-white px-3 py-2 rounded-lg">


✕


</button>





</div>


</div>



`;




            container.insertAdjacentHTML(
                'beforeend',
                html
            );



            index++;


            updateUrutan();


        }







        function removeField(button) {


            let field = button.closest('.field-item');



            if (field) {


                field.remove();


                updateUrutan();


            }


        }






        function updateUrutan() {


            let fields =
                document.querySelectorAll('.field-item');



            fields.forEach((field, i) => {


                let input =
                    field.querySelector(
                        'input[name*="[urutan]"]'
                    );



                if (input) {


                    input.value = i + 1;


                }



            });



        }
    </script>



@endsection
