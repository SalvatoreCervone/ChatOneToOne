<?php


namespace SalvatoreCervone\ChatOneToOne\Controllers;

use Inertia\Inertia;
use App\Http\Controllers;

class ChatMessageController extends Controller
{
    function index()
    {
        return  Inertia::render('Dashboard', [
            'authuser' => auth()->user(),
            // 'users' => User::whereNot('id', auth()->id())->get(),
            // 'friend' => $friend

        ]);
    }
}
