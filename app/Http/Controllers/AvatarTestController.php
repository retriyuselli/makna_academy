<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AvatarTestController extends Controller
{
    public function index()
    {
        $googleUsers = User::whereNotNull('google_id')->limit(5)->get();
        $regularUsers = User::whereNull('google_id')->limit(5)->get();
        
        return view('avatar-test', compact('googleUsers', 'regularUsers'));
    }
}
