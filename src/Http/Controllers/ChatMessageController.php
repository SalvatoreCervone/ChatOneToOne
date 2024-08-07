<?php

// namespace App\Http\Controllers;

namespace JohnDoe\BlogPackage\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{
    function index(User $friend = null)
    {
        return  Inertia::render('Dashboard', [
            'authuser' => auth()->user(),
            // 'users' => User::whereNot('id', auth()->id())->get(),
            // 'friend' => $friend

        ]);
    }
}
