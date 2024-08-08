<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Http\Controllers\ChatMessageController;




class ChatMessageController extends Controller
{
    function index()
    {
        return  Inertia::render('chatonetoone/ChatComponent2', [
            'authuser' => auth()->user(),
            // 'users' => User::whereNot('id', auth()->id())->get(),
            // 'friend' => $friend

        ]);
    }
}
