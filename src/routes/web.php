<?php

use App\Models\User;
use Carbon\Carbon;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Route;
use SalvatoreCervone\ChatOneToOne\Http\Controllers\ChatMessageController;
use SalvatoreCervone\ChatOneToOne\Models\ChatMessage;
// use SalvatoreCervone\ChatOneToOne\Events\MessageSent;;

// Route::prefix('http://10.119.179.171')->group(function () {
// require __DIR__ . '/auth.php';
Route::middleware(['web', 'auth'])->group(function () {

    Route::get('/users', function () {
        return User::where('id', "!=", auth()->user()->id)->get();
    })->name('users');

    Route::get('/', [ChatMessageController::class, 'index'])->name('dashboard');

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
// });

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });
