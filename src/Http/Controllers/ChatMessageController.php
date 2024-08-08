<?php

namespace App\Http\Controllers;
use Inertia\Inertia;





class ChatMessageController extends Controller
{
    function index()
    {
        return  Inertia::render('chatonetoone/ChatComponent2', [
            'currentUser' => auth()->user(),
            // 'users' => User::whereNot('id', auth()->id())->get(),
            // 'friend' => $friend

        ]);
    }
}
