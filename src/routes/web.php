<?php

use Carbon\Carbon;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Route;
use SalvatoreCervone\ChatOneToOne\Http\Controllers\ChatMessageController;
use SalvatoreCervone\ChatOneToOne\Models\ChatMessage;

Route::middleware(['web', 'auth'])->group(function () {
    $model_user = app(config('chatonetoone.model_user'));
    $column_user = config('chatonetoone.columns_user');
    Route::get('/users', function () use ($model_user) {

        return $model_user::where('id', "!=", auth()->user()->id)->get();
    })->name('users');

    Route::get('/users/chat', function () use ($column_user) {
        $receiver_lista = ChatMessage::select($column_user)
            ->where('sender_id', auth()->id())
            //->orWhere('receiver_id', auth()->id())
            ->join('users', 'users.id', '=', 'receiver_id');


        $sender_lista = ChatMessage::select($column_user)
            ->where('receiver_id', auth()->id())
            //->orWhere('receiver_id', auth()->id())
            ->join('users', 'users.id', '=', 'sender_id')
            ->union($receiver_lista)
            //->orderBy('chat_messages.created_at', 'desc')
            ->distinct()->get();

        return $sender_lista;
    })->name('userschat');

    Route::get('/users/chat/count', function () use ($column_user) {
        return ChatMessage::select($column_user)
            ->join('users', 'users.id', '=', 'sender_id')
            ->where('receiver_id', auth()->id())
            ->whereNull('read')
            ->orderBy('created_at', 'desc')
            ->count();
    })->name('userschatcount');

    Route::get('/chats', [ChatMessageController::class, 'index'])->name('chats');   

    Route::get('/messages/{friend_id}', function ($friend_id) {
        return ChatMessage::query()
            ->where(function ($query) use ($friend_id) {
                $query->where('sender_id', auth()->id())
                    ->where('receiver_id', $friend_id);
            })
            ->orWhere(function ($query) use ($friend_id) {
                $query->where('sender_id', $friend_id)
                    ->where('receiver_id', auth()->id());
            })
            ->with(['sender', 'receiver'])
            ->orderBy('id', 'asc')
            ->get();
    });


    Route::post('/messages', function () {
        $friend_id = request()->input('friend_id');
        $message = ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $friend_id,
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
