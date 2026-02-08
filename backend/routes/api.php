<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatStreamController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MessageController;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::post('/auth/login', [AuthController::class, 'login']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);

    return ['token' => $token->plainTextToken];
});

Route::post('/auth/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return response()->json([
        'message' => 'If the email exists, a password reset link has been sent.'
    ]);
})->middleware('guest');


Route::post('/auth/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    if ($status === Password::PASSWORD_RESET) {
        return response()->json([
            'message' => __('passwords.reset'),
        ], 200);
    }

    return response()->json([
        'message' => __('passwords.user'),
        'errors' => [
            'email' => [__($status)],
        ],
    ], 422);
})->middleware('guest')->name('password.update');


Route::apiResource('events', EventController::class)->middleware('auth:sanctum');

Route::post('/chats/{chat}/messages', [MessageController::class, 'send']);
Route::get('/chats/{chat}', [MessageController::class, 'show']);
Route::get('/chat', [MessageController::class, 'createOrGet']);
Route::post('/chats/{chat}/transfer', [MessageController::class, 'transferToHuman']);