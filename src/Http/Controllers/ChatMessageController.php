<?php


namespace SalvatoreCervone\ChatOneToOne\Http\Controllers;

use Inertia\Inertia;
use App\Http\Controllers\Controller;

class ChatMessageController extends Controller
{
    function index()
    {
        return  Inertia::render('chatonetoone/ChatComponent', [
            'currentUser' => auth()->user(),
            // 'users' => User::whereNot('id', auth()->id())->get(),
            // 'friend' => $friend

        ]);
    }
}
