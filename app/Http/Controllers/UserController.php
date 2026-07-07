<?php

namespace App\Http\Controllers;
use App\Models\JenisSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\HistoryPengajuanService;
use App\Models\SuratMaster;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        return view('users.dashboard');
    }

    public function history(Request $request)
    {
        $query = SuratMaster::with('jenisSurat')->where('user_id', Auth::id());

        if ($request->jenis_surat_id) {
            $query->where('jenis_surat_id', $request->jenis_surat_id);
        }

        if ($request->search) {
            $search = $request->search;
            $query->whereHas('jenisSurat', function($q) use ($search) {
                $q->where('nama_jenis', 'like', "%{$search}%");
            });
        }

        $jenisSurats = JenisSurat::all();
        $pengajuan = $query->latest()->paginate(10)->withQueryString();

        return view('users.history', compact('pengajuan', 'jenisSurats'));
    }

    public function profile()
    {
        $user = Auth::user();

        return view(
            'users.profile',
            compact('user')
        );
    }
    public function editProfile()
    {
        $user = Auth::user();

        return view(
            'users.edit-profile',
            compact('user')
        );
    }


    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'nullable'
        ]);


        $user = User::find(Auth::id());


        $user->name = $request->name;
        $user->phone = $request->phone;


        $user->save();


        return redirect()
            ->route('user.profile')
            ->with('success','Profile berhasil diperbarui');
    }
    public function pengajuan(Request $request)
    {
        $user = auth::user();

        $jenisSurat = JenisSurat::all();

        $selectedSurat = null;
        $fields = [];

        if ($request->filled('jenis_surat_id')) {
            $selectedSurat = JenisSurat::find($request->jenis_surat_id);

            $fields = DB::table('jenis_surat_field_form')
                ->where('jenis_surat_id', $request->jenis_surat_id)
                ->orderBy('urutan')
                ->get();
        }

        return view('users.pengajuan', compact(
            'user',
            'jenisSurat',
            'selectedSurat',
            'fields'
        ));
    }

    public function storePengajuan(Request $request)
{
    DB::transaction(function () use ($request) {

        // 1. SURAT MASTER
        $suratId = DB::table('surat_master')->insertGetId([
            'user_id' => Auth::id(),
            'jenis_surat_id' => $request->jenis_surat_id,
            'nomor_surat' => null,
            'status' => 'pending',
            'file_hasil' => null,
            'catatan_revisi' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. FIELD CONFIG
        $fields = DB::table('jenis_surat_field_form')
            ->where('jenis_surat_id', $request->jenis_surat_id)
            ->orderBy('urutan')
            ->get();

        // 3. LOOP FIELD
        foreach ($fields as $field) {

            $value = $request->fields[$field->id] ?? null;

            // =====================
            // LIST ANGGOTA
            // =====================
            if ($field->field_type == 'list_anggota') {

                $file = $value['file'] ?? null;
                if (!$file) continue;

                $path = $file->store('excel/anggota');

                DB::table('surat_file')->insert([
                    'surat_id' => $suratId,
                    'user_id' => auth::id(),
                    'nama_file' => $file->getClientOriginalName(),
                    'path_file' => $path,
                    'file_type' => 'excel',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $rows = Excel::toArray([], $file)[0] ?? [];
                $data = [];
                $seenIdentitas = [];

                foreach ($rows as $i => $row) {
                    if ($i === 0) continue;
                    if (!isset($row[0])) continue;

                    $identitas = $row[1] ?? '';
                    
                    if (in_array($identitas, $seenIdentitas)) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'fields.'.$field->id => 'Terdapat duplikasi NIM/NIDN '.$identitas.' pada file Excel.'
                        ]);
                    }
                    $seenIdentitas[] = $identitas;

                    $data[] = [
                        'surat_id' => $suratId,
                        'nama' => $row[0],
                        'identitas' => $identitas,
                        'keterangan' => $row[2] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if (!empty($data)) {
                    DB::table('surat_anggota')->insert($data);
                }

                continue;
            }

            // =====================
            // LIST KEPANITIAAN
            // =====================
            if ($field->field_type == 'list_kepanitiaan') {

                $file = $value['file'] ?? null;
                if (!$file) continue;

                $path = $file->store('excel/kepanitiaan');

                DB::table('surat_file')->insert([
                    'surat_id' => $suratId,
                    'user_id' => auth::id(),
                    'nama_file' => $file->getClientOriginalName(),
                    'path_file' => $path,
                    'file_type' => 'excel',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $rows = Excel::toArray([], $file)[0] ?? [];
                $data = [];
                $seenIdentitas = [];

                foreach ($rows as $i => $row) {
                    if ($i === 0) continue;
                    if (!isset($row[0])) continue;

                    $identitas = $row[1] ?? '';
                    
                    if (in_array($identitas, $seenIdentitas)) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'fields.'.$field->id => 'Terdapat duplikasi NIM/NIDN '.$identitas.' pada file Excel.'
                        ]);
                    }
                    $seenIdentitas[] = $identitas;

                    $data[] = [
                        'surat_id' => $suratId,
                        'nama' => $row[0],
                        'identitas' => $identitas,
                        'jabatan' => $row[4] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if (!empty($data)) {
                    DB::table('surat_panitia')->insert($data);
                }

                continue;
            }

            // =====================
            // DEFAULT (TEXT / DATE)
            // =====================
            DB::table('surat_detail')->insert([
                'surat_id' => $suratId,
                'field_name' => $field->field_name,
                'field_value' => is_array($value) ? json_encode($value) : $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        }
        HistoryPengajuanService::create($suratId, auth::id(), 'Created');

        $surat = SuratMaster::find($suratId);
        $moUsers = User::whereHas('role', function($q) {
            $q->where('role_name', 'mo');
        })->get();

        foreach($moUsers as $mo) {
            $mo->notify(new \App\Notifications\PengajuanBaruNotification($surat));
        }
    });

    return back()->with('success', 'Pengajuan berhasil disimpan');
}

public function editPengajuan(Request $request, $id)
{
    $user = Auth::user();
    $pengajuan = SuratMaster::with(['details', 'anggota', 'panitia'])->where('user_id', $user->id)->findOrFail($id);

    if ($pengajuan->status !== 'pending' && $pengajuan->status !== 'revisi') {
        return redirect()->route('user.history')->with('error', 'Pengajuan tidak dapat diubah lagi.');
    }

    $jenisSurat = JenisSurat::all();
    $selectedSurat = JenisSurat::find($pengajuan->jenis_surat_id);
    $fields = DB::table('jenis_surat_field_form')
        ->where('jenis_surat_id', $pengajuan->jenis_surat_id)
        ->orderBy('urutan')
        ->get();

    // Map details for the form
    $detailValues = [];
    foreach($pengajuan->details as $d) {
        $detailValues[$d->field_name] = $d->field_value;
    }

    return view('users.edit-pengajuan', compact(
        'user', 'jenisSurat', 'selectedSurat', 'fields', 'pengajuan', 'detailValues'
    ));
}

public function updatePengajuan(Request $request, $id)
{
    $pengajuan = SuratMaster::where('user_id', Auth::id())->findOrFail($id);

    if ($pengajuan->status !== 'pending' && $pengajuan->status !== 'revisi') {
        return redirect()->route('user.history')->with('error', 'Pengajuan tidak dapat diubah lagi.');
    }

    DB::transaction(function () use ($request, $pengajuan) {
        $pengajuan->update([
            'status' => 'pending', // kembali pending setelah direvisi
            'updated_at' => now(),
        ]);

        $fields = DB::table('jenis_surat_field_form')
            ->where('jenis_surat_id', $pengajuan->jenis_surat_id)
            ->orderBy('urutan')
            ->get();

        foreach ($fields as $field) {
            $value = $request->fields[$field->id] ?? null;

            if ($field->field_type == 'list_anggota') {
                $file = $value['file'] ?? null;
                if ($file) {
                    $path = $file->store('excel/anggota');
                    DB::table('surat_file')->insert([
                        'surat_id' => $pengajuan->id,
                        'user_id' => Auth::id(),
                        'nama_file' => $file->getClientOriginalName(),
                        'path_file' => $path,
                        'file_type' => 'excel',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $rows = Excel::toArray([], $file)[0] ?? [];
                    $data = [];
                    $seenIdentitas = [];

                    foreach ($rows as $i => $row) {
                        if ($i === 0 || !isset($row[0])) continue;
                        
                        $identitas = $row[1] ?? '';
                        
                        if (in_array($identitas, $seenIdentitas)) {
                            throw \Illuminate\Validation\ValidationException::withMessages([
                                'fields.'.$field->id => 'Terdapat duplikasi NIM/NIDN '.$identitas.' pada file Excel.'
                            ]);
                        }
                        $seenIdentitas[] = $identitas;

                        $data[] = [
                            'surat_id' => $pengajuan->id,
                            'nama' => $row[0],
                            'identitas' => $identitas,
                            'keterangan' => $row[2] ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    if (!empty($data)) {
                        DB::table('surat_anggota')->where('surat_id', $pengajuan->id)->delete();
                        DB::table('surat_anggota')->insert($data);
                    }
                }
                continue;
            }

            if ($field->field_type == 'list_kepanitiaan') {
                $file = $value['file'] ?? null;
                if ($file) {
                    $path = $file->store('excel/kepanitiaan');
                    DB::table('surat_file')->insert([
                        'surat_id' => $pengajuan->id,
                        'user_id' => Auth::id(),
                        'nama_file' => $file->getClientOriginalName(),
                        'path_file' => $path,
                        'file_type' => 'excel',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $rows = Excel::toArray([], $file)[0] ?? [];
                    $data = [];
                    $seenIdentitas = [];

                    foreach ($rows as $i => $row) {
                        if ($i === 0 || !isset($row[0])) continue;

                        $identitas = $row[1] ?? '';
                        
                        if (in_array($identitas, $seenIdentitas)) {
                            throw \Illuminate\Validation\ValidationException::withMessages([
                                'fields.'.$field->id => 'Terdapat duplikasi NIM/NIDN '.$identitas.' pada file Excel.'
                            ]);
                        }
                        $seenIdentitas[] = $identitas;

                        $data[] = [
                            'surat_id' => $pengajuan->id,
                            'nama' => $row[0],
                            'identitas' => $identitas,
                            'jabatan' => $row[4] ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    if (!empty($data)) {
                        DB::table('surat_panitia')->where('surat_id', $pengajuan->id)->delete();
                        DB::table('surat_panitia')->insert($data);
                    }
                }
                continue;
            }

            // Update text/date fields
            if ($value !== null) {
                DB::table('surat_detail')
                    ->updateOrInsert(
                        ['surat_id' => $pengajuan->id, 'field_name' => $field->field_name],
                        ['field_value' => is_array($value) ? json_encode($value) : $value, 'updated_at' => now()]
                    );
            }
        }
        HistoryPengajuanService::create($pengajuan->id, Auth::id(), 'Updated');
    });

    return redirect()->route('user.history')->with('success', 'Pengajuan berhasil diubah');
}


public function downloadSurat($id)
{
    $pengajuan = SuratMaster::where('user_id', Auth::id())
        ->findOrFail($id);


    if($pengajuan->status != 'approved'){

        return redirect()
            ->back()
            ->with(
                'error',
                'Surat belum disetujui'
            );

    }


    if(!$pengajuan->file_hasil){

        return redirect()
            ->back()
            ->with(
                'error',
                'File surat belum tersedia'
            );

    }


    if(!file_exists($pengajuan->file_hasil)){

        return redirect()
            ->back()
            ->with(
                'error',
                'File surat tidak ditemukan'
            );

    }


    return response()->download(
        $pengajuan->file_hasil
    );
}

}
