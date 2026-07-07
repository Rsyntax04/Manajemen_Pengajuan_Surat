<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        DB::transaction(function () use ($request, $id) {

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
        });
    }
}
