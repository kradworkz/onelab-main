<?php

use App\Models\ChatConversation;
use App\Models\ChatGroupUser;
use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat-room-{id}', function ($user, $id) {
    $conversation = ChatConversation::findOrFail($id);
    if($conversation->first_id == $user->id || $conversation->second_id == $user->id){
        return $user;
    }
    return false;
});

Broadcast::channel('group-chat-room-{id}', function ($user, $id) {
    
    $check = ChatGroupUser::where('group_id',$id)->where('user_id',$user->id)->count();
    if($check > 0){
        return true;
    }else{
        return false;
    }
});

Broadcast::channel('Online', function ($user) {
    return $user;
});