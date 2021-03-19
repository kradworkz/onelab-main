<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\ChatGroupUser;
use App\Models\ChatConversation;
use App\Models\ChatGroupConversation;
use Illuminate\Http\Request;
use App\Events\NewConversationMessage;
use App\Http\Resources\Chatbox\ChatResource;
use App\Http\Resources\Chatbox\ListResource;
use App\Http\Resources\Chatbox\GroupResource;
use App\Http\Resources\Chatbox\GroupChatResource;
use App\Http\Resources\Chatbox\ContactResource;

class ChatController extends Controller
{
    public function conversation(Request $request){
        $receiver_id = Auth::user()->id;
        $sender_id = $request->input('id');

        $conversation = ChatConversation::whereIn('first_id', [$receiver_id,$sender_id])->whereIn('second_id', [$receiver_id,$sender_id])->first();
       
        if(empty($conversation)){
            $convo = new ChatConversation;
            $convo->first_id = $sender_id;
            $convo->second_id = $receiver_id;
            $convo->save();
        }
    
        return new ChatResource($conversation);
    }

    public function contacts(){
        $id = Auth::user()->id;
        $conversation = User::where('id','!=',$id)->get();
        
        return ContactResource::collection($conversation);
    }

    public function send(Request $request){
        $id = $request->input('conversationId');
        $text = $request->input('text');
        $channel = 'chat-room-'.$id;
        $files = [];

        $conversation = ChatConversation::findOrFail($id);
        $conversation->messages()->create([
            'text' => $text,
            'user_id' => Auth::user()->id
        ]);

        $test = broadcast(new NewConversationMessage($text, $channel, $files));

        return true;
    }

    public function groups(){
        $id = Auth::user()->id;
        $ids = ChatGroupUser::select('group_id')->where('user_id',$id)->get();
        $groups = ChatGroupConversation::whereIn('id',$ids)->get();
        
        return GroupResource::collection($groups);
    }


    public function groupConversation(Request $request){
        //$id = Auth::user()->id;
        $id = $request->input('id');
        $conversation = ChatGroupConversation::where('id', $id)->first();

        return new GroupChatResource($conversation);
    }

    public function createGroup(Request $request){

        $data = $request->isMethod('put') ? ChatGroupConversation::findOrFail($request->input('id')) : new ChatGroupConversation;
        $data->name = ucwords(strtolower($request->input('name')));
        $data->save();

        return new DefaultResource($data);
    }

    public function sendGroup(Request $request){
        $id = $request->input('conversationId');
        $text = $request->input('text');
        $channel = 'group-chat-room-'.$id;
        $files = [];

        $conversation = ChatGroupConversation::findOrFail($id);
        $conversation->messages()->create([
            'text' => $text,
            'user_id' => Auth::user()->id
        ]);

        broadcast(new NewConversationMessage($text, $channel, $files));

        return true;
    }

    public function addUser(Request $request){

        $data = $request->isMethod('put') ? ChatGroupUser::findOrFail($request->input('id')) : new ChatGroupUser;
        
        $users = $request->input('users');
        if(!empty($users)){
            foreach($users as $user){
                
                if(empty($user['id'])) { 
                    $data = new ChatGroupUser;
                }else{
                    $id = $user['id'];
                    $data = ChatGroupUser::find($id);
                }
                $data->user_id = $user['id'];
                $data->group_id = $request->input('group');
                $data->save();
            }
        }

        return new DefaultResource($data);
    }

    public function removeUser($id){
        $data = ChatGroupUser::findOrFail($id);
        if($data->delete()){
            return new DefaultResource($data);   
        }
    }

    public function search(Request $request)
    {
        $keyword = $request->input('word');
        
        $id = Auth::user()->id;
        $conversation = User::where('id','!=',$id)->whereHas('agency',function($query) use ($keyword) {
            return $query->where('name', 'LIKE', '%'.$keyword.'%')
            ->orWhere('acronym','like','%'.$keyword.'%');
        })->get();
        
        return ListResource::collection($conversation);
    }
}
