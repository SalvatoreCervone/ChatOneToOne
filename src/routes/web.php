<?php

use App\Models\User;
use Carbon\Carbon;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Route;
use SalvatoreCervone\ChatOneToOne\Http\Controllers\ChatMessageController;
use SalvatoreCervone\ChatOneToOne\Models\ChatMessage;

Route::middleware(['web', 'auth'])->group(function () {

    Route::get('/users', function () {
        $model_user = app(config('chatonetoone.model_user'));
        return $model_user::where('id', "!=", auth()->user()->id)->get();
    })->name('users');

    Route::get('/users/chat', function () {
        $receiver_lista = ChatMessage::select(config('chatonetoone.columns_user'))
            ->where('sender_id', auth()->id())
            //->orWhere('receiver_id', auth()->id())
            ->join('users', 'users.id', '=', 'receiver_id');


        $sender_lista = ChatMessage::select("users.id", 'users.name', 'users.email')
            ->where('receiver_id', auth()->id())
            //->orWhere('receiver_id', auth()->id())
            ->join('users', 'users.id', '=', 'sender_id')
            ->union($receiver_lista)
            //->orderBy('chat_messages.created_at', 'desc')
            ->distinct()
            ->get();

        return $sender_lista;
    })->name('userschat');

    Route::get('/users/chat/count', function () {
        return ChatMessage::select("users.id", 'users.name', 'users.email', 'chat_messages.created_at')
            ->join('users', 'users.id', '=', 'sender_id')
            ->where('receiver_id', auth()->id())
            ->whereNull('read')
            ->orderBy('created_at', 'desc')
            ->count();
    })->name('userschatcount');

    Route::get('/chats', [ChatMessageController::class, 'index'])->name('chats');

    Route::get('/chat/{friend}', function (User $friend) {
        return view('chat', [
            'friend' => $friend
        ]);
    })->name('chat');

    Route::get('/messages/{friend}', function (User $friend) {
        return ChatMessage::query()
            ->where(function ($query) use ($friend) {
                $query->where('sender_id', auth()->id())
                    ->where('receiver_id', $friend->id);
            })
            ->orWhere(function ($query) use ($friend) {
                $query->where('sender_id', $friend->id)
                    ->where('receiver_id', auth()->id());
            })
            ->with(['sender', 'receiver'])
            ->orderBy('id', 'asc')
            ->get();
    });
    Route::post('/messages/{friend}', function (User $friend) {
        $message = ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $friend->id,
            'text' => request()->input('message')
        ]);

        broadcast(new MessageSent($message));

        return  $message;
    });
    Route::post('/messages/receiver/read', function () {
        $friend_id = request()->input('friend_id');
        $readtime = request()->input('readtime');
        return ChatMessage::query()
            ->where(function ($q) use ($friend_id) {
                //TROVA LA CHAT DOVE SONO CONINVOLTI CHI è AUTENTICATO
                //E L'AMICO INTERESSATO
                $q->where(function ($query) use ($friend_id) {
                    $query->where('sender_id', auth()->id())
                        ->where('receiver_id', $friend_id);
                })
                    ->orWhere(function ($query) use ($friend_id) {
                        $query->where('sender_id', $friend_id)
                            ->where('receiver_id', auth()->id());
                    });
            })
            ->where('receiver_id', auth()->id())
            ->where('created_at', '<=', $readtime)
            ->whereNull('read')
            ->update(['read' => Carbon::now()->format('Y-m-d')]);
    });
    Route::get('/messages/receiver/toread/{friend_id}', function ($friend_id) {

        return ChatMessage::query()
            ->where(function ($q) use ($friend_id) {
                //TROVA LA CHAT DOVE SONO CONINVOLTI CHI è AUTENTICATO
                //E L'AMICO INTERESSATO
                $q->where(function ($query) use ($friend_id) {
                    $query->where('sender_id', auth()->id())
                        ->where('receiver_id', $friend_id);
                })
                    ->orWhere(function ($query) use ($friend_id) {
                        $query->where('sender_id', $friend_id)
                            ->where('receiver_id', auth()->id());
                    });
            })
            ->where('receiver_id', auth()->id())

            ->whereNull('read')
            ->count();
    });
});
