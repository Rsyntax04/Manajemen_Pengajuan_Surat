<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneratorController extends Controller
{
    public function generate($id)
    {
        $service = new SuratGeneratorService();

        $path = $service->generate($id);

        return response()->download($path);
    }

    public function download($id)
    {
        $surat = SuratMaster::findOrFail($id);

        return response()->download(
            storage_path("app/surat/" . $surat->file_hasil)
        );
    }
}
