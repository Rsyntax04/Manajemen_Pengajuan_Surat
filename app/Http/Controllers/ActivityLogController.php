<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    // function reusable untuk simpan activity
    public static function store($activity, $description = null)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => $activity,
            'description' => $description
        ]);
    }

    public function index(Request $request)
    {
        $search = $request->search;

        $activities = ActivityLog::with('user')
    
            ->when($search, function ($query) use ($search) {
    
                $query->where('activity', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($q) use ($search) {
    
                          $q->where('name', 'like', "%{$search}%");
    
                      });
    
            })
    
            ->latest()
            ->paginate(10);
    
        return view('admin.activity_log', compact('activities', 'search'));
    }
}