<?php

namespace App\Events;

use App\Models\Task;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, InteractsWithBroadcasting;

    protected $task;
    protected $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Task $task, User $user)
    {
        $this->task = $task;
        $this->user = $user;
    }

    /**
     * The name of the queue on which to place the broadcasting job.
     *
     * @return string
     */
    public function broadcastQueue()
    {
        return 'default';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('task-updated');
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'TaskUpdated';
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastWith()
    {
        return ['title' => 'La tâche n° ' . $this->task->id . ' a été mise à jour', 'task' => $this->task->id, 'user' => $this->user->id];
    }
}
