<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Student;
use App\Models\Trainer;
use App\Channels\PrivateChatChannel;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });
// Broadcast::channel('chat', function () {
//     return true;
// });
// Broadcast::channel('private-chat.student.{studentId}', function ($user, $studentId) {
//     // Add your authorization logic here to check if the user can access the student's chat.
//     $student = Student::find($studentId);
//     return $student && $user->id === $student->id;
// });

// Broadcast::channel('private-chat.trainer.{trainerId}', function ($user, $trainerId) {
//     // Add your authorization logic here to check if the user can access the trainer's chat.
//     $trainer = Trainer::find($trainerId);
//     return $trainer && $user->id === $trainer->id;
// });


// Broadcast::channel('private-chat.student.{studentId}', function ($user, $studentId) {
//     $student = Student::find($studentId);
//     return $student && $user->id === $student->id;
// });

// Broadcast::channel('private-chat.trainer.{trainerId}', function ($user, $trainerId) {
//     $trainer = Trainer::find($trainerId);
//     return $trainer && $user->id === $trainer->id;
// });


// Broadcast::channel('chat.{studentId}.{trainerId}', function ($user, $studentId, $trainerId) {
//     // Implement your authorization logic here, e.g., check if the user is the student or trainer

//     return true;
// });


// Broadcast::channel('chat.{studentId}.{trainerId}', function ($user, $studentId, $trainerId) {
//     // Implement your authorization logic here
//     // For example, check if the user is the student or the trainer
//     // You may need to adjust this logic based on your application's authentication and user model setup

//     if ($user instanceof \App\Models\Student && $user->id === (int) $studentId) {
//         return true;
//     }

//     if ($user instanceof \App\Models\Trainer && $user->id === (int) $trainerId) {
//         return true;
//     }

//     return false;
// });



// Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
//     // Add your authorization logic here to ensure the user can access the chat
//     return true; // or false based on your requirements
// });

// Broadcast::channel('private-chat.{adminId}.{studentId}', function ($user, $adminId, $studentId) {
//     // Add your authorization logic here to ensure the user can access the private chat
//     return $user->isAdmin() && $user->id === $adminId || $user->isStudent() && $user->id === $studentId;
// });
Broadcast::channel('private-chat.{userId}.{studentId}', PrivateChatChannel::class);
// Broadcast::routes(['middleware' => ['api']]);



// Route::post('/broadcasting/auth', [BroadcastAuthController::class, 'authenticate']);

