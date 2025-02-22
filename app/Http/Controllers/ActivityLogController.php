<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index()
    {
        $activities = Activity::orderBy('created_at', 'desc')->get();

        return view('activity_logs.index', compact('activities'));
    }
}
