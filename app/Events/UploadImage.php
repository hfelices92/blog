<?php

namespace App\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UploadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post;
    public $imagePath;
    /**
     * Create a new event instance.
     */
    public function __construct($post, $imagePath)
    {
        $this->post = $post;
        $this->imagePath = $imagePath;
    }
   

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
