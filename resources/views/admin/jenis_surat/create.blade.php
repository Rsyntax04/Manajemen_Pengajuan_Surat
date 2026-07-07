@extends('layouts.app')

@section('title', 'Tambah Jenis Surat')

@section('page-title', 'Tambah Jenis Surat')


@section('content')


    <div class="bg-white rounded-2xl shadow-md p-6">


        <form method="POST" action="{{ route('jenis-surat.store') }}" enctype="multipart/form-data">


            @csrf



            <h2 class="text-2xl font-bold mb-5">

                Tambah Jenis Surat

            </h2>





            <div class="grid grid-cols-2 gap-5">



                <div>

                    <label class="font-semibold">
                        Nama Surat
                    </label>


                    <input name="nama_jenis" class="w-full border rounded-xl p-3" required>

                </div>




                <div>

                    <label class="font-semibold">
                        Kode Surat
                    </label>


                    <input name="kode_surat" class="w-full border rounded-xl p-3" required>

                </div>



            </div>






            <div class="mt-5">


                <label class="font-semibold">

                    Template Surat (.docx)

                </label>



                <input type="file" name="template_file" accept=".doc,.docx" class="w-full border rounded-xl p-3"
                    required>



            </div>

            <h3 class="font-bold text-lg mt-8 mb-4 border-b pb-2">Informasi Penandatangan</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                <div>
                    <label class="font-semibold text-sm text-gray-700">Nama Penandatangan</label>
                    <input type="text" name="penandatangan_nama" class="w-full border rounded-xl p-3 mt-1" placeholder="Contoh: Dr. Ir. Budi Santoso, M.T.">
                </div>
                <div>
                    <label class="font-semibold text-sm text-gray-700">NIP / NIK</label>
                    <input type="text" name="penandatangan_nip" class="w-full border rounded-xl p-3 mt-1" placeholder="Contoh: 198001012005011001">
                </div>
                <div>
                    <label class="font-semibold text-sm text-gray-700">Jabatan</label>
                    <input type="text" name="penandatangan_jabatan" class="w-full border rounded-xl p-3 mt-1" placeholder="Contoh: Dekan / Ketua Program Studi">
                </div>
            </div>

            <hr class="my-6">






            <div class="flex justify-between items-center mb-4">


                <h3 class="font-bold text-lg">

                    Field Form

                </h3>




                <button type="button" onclick="addField()"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">


                    + Tambah Field


                </button>


            </div>








            <div id="fields">






                {{-- FIELD DEFAULT --}}

                <div class="field-item bg-gray-50 border rounded-xl p-4 mb-4">



                    <div class="grid grid-cols-5 gap-3 items-center">





                        <input name="fields[0][field_name]" placeholder="Nama Field" class="border rounded-lg p-2">






                        <select name="fields[0][field_type]" class="border rounded-lg p-2">


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


                            <input type="checkbox" name="fields[0][is_required]" value="1">


                            Required


                        </label>





                        {{-- AUTO URUTAN --}}

                        <input type="hidden" name="fields[0][urutan]" value="1">





                        <div class="text-gray-400 text-sm text-center">


                            Default


                        </div>





                    </div>


                </div>





            </div>







            <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg">


                Simpan


            </button>





        </form>


    </div>

@endsection





@section('scripts')


    <script>
        let index = 1;





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

value="${index + 1}">







<button

type="button"

onclick="removeField(this)"

class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg">


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



            let field =
                button.closest('.field-item');





            if (field) {



                field.remove();




                updateUrutan();



            }



        }








        function updateUrutan() {



            let fields =
                document.querySelectorAll('.field-item');





            fields.forEach((field, index) => {



                let urutan =
                    field.querySelector(
                        'input[name*="[urutan]"]'
                    );




                if (urutan) {


                    urutan.value = index + 1;


                }



            });



        }
    </script>


@endsection
