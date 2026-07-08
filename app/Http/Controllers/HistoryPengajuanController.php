<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\StatusPengajuanMail;

class HistoryPengajuanController extends Controller
{
    function createHistory($suratId, $userId, $status)
    {
        DB::table('history_pengajuan')->insert([
            'surat_id' => $suratId,
            'user_id' => $userId,
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    
    public function updateStatus(Request $request, $id)
    {
         dd('aaaaaaaaaaaa');
        DB::transaction(function () use ($request, $id) {
             dd('b');
            DB::table('surat_master')
                ->where('id', $id)
                ->update([
                    'status' => $request->status
                ]);

            DB::table('history_pengajuan')
                ->where('surat_id', $id)
                ->update([
                    'status' => $request->status
                ]);

            DB::table('activity_logs')->insert([
                'surat_id' => $id,
                'actor_id' => auth()->id(),
                'actor_role' => auth()->user()->role,
                'activity' => $request->status,
                'description' => 'Status diubah oleh MO',
                'created_at' => now(),
            ]);
            dd('aaaaaaaaaaaa');
            // Ambil data mahasiswa
            $surat = DB::table('surat_master')
                ->join('users', 'users.id', '=', 'surat_master.user_id')
                ->join('jenis_surats', 'jenis_surats.id', '=', 'surat_master.jenis_surat_id')
                ->select(
                    'surat_master.*',
                    'users.email',
                    'users.name as nama_mahasiswa',
                    'jenis_surats.nama as jenis_surat'
                )
                ->where('surat_master.id', $id)
                ->first();

            // Kirim email
            if ($surat && $surat->email) {
                Mail::to($surat->email)
                    ->send(new StatusPengajuanMail($surat));
            }
        });

        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }
}
