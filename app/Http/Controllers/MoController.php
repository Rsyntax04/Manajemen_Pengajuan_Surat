<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HistoryPengajuan;
use App\Models\SuratMaster;
use App\Models\JenisSurat;
use PhpOffice\PhpWord\IOFactory;
use App\Services\SuratGeneratorService;
use Illuminate\Support\Facades\Mail;
use App\Mail\StatusPengajuanMail;

class MoController extends Controller
{
    public function index()
    {
        $latest = SuratMaster::with(['user', 'jenisSurat'])->latest()->take(5)->get();
        return view('mo.dashboard', compact('latest'));
    }

    public function history(Request $request)
    {
        $query = SuratMaster::with([
            'user',
            'jenisSurat'
        ]);


        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        if ($request->jenis_surat_id) {
            $query->where('jenis_surat_id', $request->jenis_surat_id);
        }

        if ($request->search) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $jenisSurats = JenisSurat::all();

        $pengajuan = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();



        return view('mo.history', compact('pengajuan', 'jenisSurats'));
    }

    public function show($id)
    {
        $pengajuan = SuratMaster::with(['user', 'jenisSurat', 'details', 'anggota', 'panitia'])->findOrFail($id);
        return view('mo.show', compact('pengajuan'));
    }

    public function approvalpage(Request $request)
    {
        $query = SuratMaster::with(['user', 'jenisSurat'])
            ->where('status', 'pending');

        if ($request->jenis_surat_id) {
            $query->where('jenis_surat_id', $request->jenis_surat_id);
        }

        if ($request->search) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $jenisSurats = JenisSurat::all();

        $pengajuan = $query->latest()->paginate(10)->withQueryString();

        return view('mo.approval', compact('pengajuan', 'jenisSurats'));
    }

    public function approve($id)
    {
        $data = SuratMaster::findOrFail($id);
        $data->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);


        $service = new SuratGeneratorService();

        $filePath = $service->generate($data->id);


        $data->update([
            'file_hasil' => $filePath
        ]);

        $jenis_surat = JenisSurat::find($data->jenis_surat_id)->nama_jenis;
        Mail::to($data->user->email)
        ->send(new StatusPengajuanMail(
            $data,$jenis_surat,
            'Pengajuan surat Anda telah disetujui.'
        ));

        return redirect()
            ->route('mo.approval')
            ->with(
                'success',
                'Pengajuan disetujui dan surat berhasil dibuat'
            );
    }

    public function reject(Request $request, $id)
    {
        $data = SuratMaster::findOrFail($id);

        $data->update([
            'status' => 'rejected',
            'catatan_revisi' => $request->catatan_revisi
        ]);

        $pesan = 'Pengajuan surat Anda dikembalikan untuk revisi. Catatan: ' . $request->catatan_revisi;
        
        $jenis_surat = JenisSurat::find($data->jenis_surat_id)->nama_jenis;
        Mail::to($data->user->email)
        ->send(new StatusPengajuanMail(
            $data,$jenis_surat,$pesan
        ));
        return back()->with('success', 'Pengajuan ditolak');
    }

    public function revisi(Request $request, $id)
    {
        $data = SuratMaster::findOrFail($id);

        $data->update([
            'status' => 'revisi',
            'catatan_revisi' => $request->catatan_revisi
        ]);
         $pesan = 'Pengajuan surat Anda ditolak. Catatan: ' . $request->catatan_revisi;

        $jenis_surat = JenisSurat::find($data->jenis_surat_id)->nama_jenis;
        Mail::to($data->user->email)
        ->send(new StatusPengajuanMail(
            $data,$jenis_surat,$pesan
        ));

        return back()->with('success', 'Pengajuan dikembalikan untuk revisi');
    }

    public function templateBuilder()
    {
        $jenisSurat = JenisSurat::all();
        return view('mo.template.builder', compact('jenisSurat'));
    }

    public function uploadWord(Request $request)
    {
        $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surat,id',
            'template_file' => 'required|mimes:docx'
        ]);

        $jenis = JenisSurat::findOrFail($request->jenis_surat_id);

        $file = $request->file('template_file');

        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('template', $fileName, 'public');

        $fullPath = storage_path('app/public/template/' . $fileName);

        // Bypass HTML Conversion untuk sementara menghindari error 'copy() is a directory'
        // pada library PhpWord di beberapa konfigurasi Windows/Zip.
        $html = "<i>Preview tidak tersedia (Bypass Mode)</i>";

        // 🔥 SAVE TEMPLATE
        $jenis->update([
            'template_file' => $fileName,
            'active_template_file' => $fileName,
            'template_html' => $html
        ]);

        return back()->with('success', 'Template berhasil diupload dan disimpan!');
    }

private function cleanHtml($html)
{
    // hapus style Word yang berlebihan
    $html = preg_replace('/style="[^"]*"/', '', $html);

    // rapikan whitespace
    $html = preg_replace('/\s+/', ' ', $html);

    return $html;
}
}

