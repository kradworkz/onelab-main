<?php

namespace App\Events;

use Auth;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Http\Resources\Chatbox\ChatmateResource;

class NewConversationMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $text;
    public $channel;
    private $files;

    public function __construct($text, $channel, $files = [])
    {
        $this->text = $text;
        $this->channel = $channel;
        $this->files = $files;
    }

    public function broadcastOn()
    {
        return new PresenceChannel($this->channel);
    }

    public function broadcastWith()
    {
        return [
            'text'       => $this->text,
            'sender'     => new ChatmateResource(Auth::user()),
            'files'      => $this->files,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
    }
}
