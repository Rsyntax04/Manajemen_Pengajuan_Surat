<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ActivityLogController;
use App\Models\ActivityLog;
use App\Models\JenisSurat;
use App\Models\JenisSuratFieldForm;
use Illuminate\Database\QueryException;

class AdminController extends Controller
{
    public function dashboard()
    {
        // total user
        $totalUsers = User::count();

        // total admin
        $totalAdmin = User::whereHas('role', function ($query) {
            $query->where('role_name', 'admin');
        })->count();

        $totalMo = User::whereHas('role', function ($query) {
            $query->where('role_name', 'mo');
        })->count();

        // total dosen
        $totalDosen = User::whereHas('role', function ($query) {
            $query->where('role_name', 'dosen');
        })->count();

        // total mahasiswa
        $totalMahasiswa = User::whereHas('role', function ($query) {
            $query->where('role_name', 'mahasiswa');
        })->count();

        $activities = ActivityLog::with('user')->latest()->take(5)->get();
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmin',
            'totalMo',
            'totalDosen',
            'totalMahasiswa',
            'activities'
        ));
    }

    public function ShowUsers(Request $request)
    {
        $search = $request->search;

        $users = User::with('role')

            ->when($search, function ($query) use ($search) {

                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('identitas', 'like', "%{$search}%");

            })

            ->latest()
            ->paginate(10);

        return view('admin.user.index', compact('users', 'search'));
    }

    public function CreateUser()
    {
        $roles = Role::all();

        return view('admin.user.create', compact('roles'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'identitas' => 'required|unique:users',
            'password' => 'required|min:6',
            'role_id' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'identitas' => $request->identitas,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'is_active' => $request->is_active ?? 1,
        ]);
        ActivityLogController::store(
            'Create User',
            'Menambahkan user baru: ' . $request->email
        );
        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::select('id', 'name', 'identitas', 'email', 'role_id', 'is_active')
                    ->findOrFail($id);

        $roles = Role::all();

        return view('admin.user.edit', compact('user', 'roles'));
    }

    public function updateUser(Request $request, $id)
    {
        
        $request->validate([
            'role_id' => 'required'
            ]);
            
        $user = User::findOrFail($id);
        $user->role_id = $request->role_id;
        $user->is_active = $request->is_active ?? 1;

        $user->save();
        ActivityLogController::store(
            'Update User',
            'Mengupdate role/status user ID: ' . $id
        );
        return redirect()->back()->with('success', 'User berhasil diupdate');
    }

    public function deleteUser($id)
    {
        $user = User::withCount(['surat', 'history', 'approvedSurat'])->findOrFail($id);

        if ($user->surat_count > 0 || $user->history_count > 0 || $user->approved_surat_count > 0) {
            return redirect()
                ->back()
                ->with('error', 'User tidak dapat dihapus karena masih memiliki data pengajuan atau riwayat terkait. Silakan nonaktifkan (suspend) user ini sebagai gantinya.');
        }

        try {
            $user->delete();

            ActivityLogController::store(
                'Delete User',
                'Menghapus user dengan ID: ' . $id
            );

            return redirect()
                ->back()
                ->with('success', 'User berhasil dihapus');

        } catch (QueryException $e) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'User tidak dapat dihapus karena masih memiliki data terkait di tabel lain'
                );
        }
    }

    public function showJenisSurat()
    {
        $jenisSurat = JenisSurat::with('fields')
        ->latest()
        ->paginate(10);

        return view(
            'admin.jenis_surat.index',
            compact('jenisSurat')
        );
    }

    public function createJenisSurat()
    {
        return view('admin.jenis_surat.create');
    }

    public function storeJenisSurat(Request $request)
    {

        $request->validate([
            'nama_jenis' => 'required',
            'kode_surat' => 'required',
            'file_template' => 'nullable|file|mimes:doc,docx,pdf',
            'fields.*.field_name' => 'required',
            'fields.*.field_type' => 'required',
            'penandatangan_nama' => 'required',
            'penandatangan_nip' => 'required',
            'penandatangan_jabatan' => 'required',
        ]);



        $jenisSurat = JenisSurat::create([
            'nama_jenis'=>$request->nama_jenis,
            'kode_surat'=>$request->kode_surat,
            'template_file'=>$request->template_file,
            'penandatangan_nama' => $request->penandatangan_nama,
            'penandatangan_nip' => $request->penandatangan_nip,
            'penandatangan_jabatan' => $request->penandatangan_jabatan,
        ]);



        foreach($request->fields as $field){

            JenisSuratFieldForm::create([

                'jenis_surat_id'
                    => $jenisSurat->id,
                'field_name'
                    => $field['field_name'],
                'field_type'
                    => $field['field_type'],
                'is_required'
                    => isset($field['is_required'])
                    ? 1
                    : 0,
                'urutan'
                    => $field['urutan'],
            ]);



        }


        ActivityLogController::store(
            'Create Jenis Surat',
            'Jenis Surat baru: ' . $request->nama_jenis
        );
        return redirect()
            ->route('jenis-surat.index')
            ->with('success','Jenis surat berhasil dibuat');

    }

    public function editJenisSurat($id)
    {

        $jenisSurat = JenisSurat::with('fields')
            ->findOrFail($id);


        return view(
            'admin.jenis_surat.edit',
            compact('jenisSurat')
        );

    }

    public function updateJenisSurat(Request $request,$id)
    {
        $request->validate([
            'nama_jenis' => 'required',
            'kode_surat' => 'required',
            'file_template' => 'nullable|file|mimes:docx',
            'fields.*.field_name' => 'required',
            'fields.*.field_type' => 'required',
            'penandatangan_nama' => 'required',
            'penandatangan_nip' => 'required',
            'penandatangan_jabatan' => 'required',
        ]);

        $jenisSurat = JenisSurat::findOrFail($id);

        $file = $request->file('template_file');

        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('template', $fileName, 'public');

        $jenisSurat->update([
            'nama_jenis'=>$request->nama_jenis,
            'kode_surat'=>$request->kode_surat,
            'template_file'=>$fileName,
            'active_template_file'=>$fileName,
            'template_html'=>$request->template_html,
            'template_json'=>$request->template_json,
            'penandatangan_nama' => $request->penandatangan_nama,
            'penandatangan_nip' => $request->penandatangan_nip,
            'penandatangan_jabatan' => $request->penandatangan_jabatan,
        ]);



        JenisSuratFieldForm::where(
            'jenis_surat_id',
            $id
        )->delete();



        foreach($request->fields ?? [] as $field)
        {

            JenisSuratFieldForm::create([

                'jenis_surat_id'=>$id,
                'field_name'=>$field['field_name'],
                'field_type'=>$field['field_type'],
                'is_required'=>$field['is_required'] ?? 0,
                'options'=>$field['options'] ?? null,
                'urutan'=>$field['urutan'] ?? 0

            ]);

        }

        ActivityLogController::store(
            'Edit Jenis Surat',
            'Mengedit jenis surat dengan ID: ' . $id
        );
        return redirect()
            ->route('jenis-surat.index')
            ->with('success','Data berhasil diubah');

    }

    public function deleteJenisSurat($id)
    {
        try {
            DB::transaction(function () use ($id) {
                JenisSuratFieldForm::where('jenis_surat_id', $id)->delete();
                JenisSurat::findOrFail($id)->delete();
            });

            ActivityLogController::store(
                'Delete Jenis Surat',
                'Menghapus jenis surat dengan ID: ' . $id
            );


            return redirect()
                ->route('jenis_surat.index')
                ->with(
                    'success',
                    'Data berhasil dihapus'
                );


        } catch (QueryException $e) {


            return redirect()
                ->route('jenis_surat.index')
                ->with(
                    'error',
                    'Jenis surat tidak dapat dihapus karena masih digunakan'
                );

        }
    }


}