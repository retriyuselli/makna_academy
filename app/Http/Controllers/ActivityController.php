<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    /**
     * Clear all activities for the authenticated user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear()
    {
        // Delete all activities for the current user
        Activity::where('user_id', Auth::id())->delete();

        return redirect()->back()->with('Semua aktivitas Anda telah berhasil dihapus!');
    }
}
