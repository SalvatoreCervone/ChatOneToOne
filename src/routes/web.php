<?php

use App\Models\User;
use Inertia\Inertia;
use App\Events\MessageSent;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatMessageController;


// Route::prefix('http://10.119.179.171')->group(function () {
// require __DIR__ . '/auth.php';
// Route::middleware(['auth'])->group(function () {

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
// });
// });

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });
