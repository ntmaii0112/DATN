<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Notifications\Events\NotificationSent;

class NotificationService
{
    public function getAll()
    {
        return Notification::all();
    }

    public function findById($id)
    {
        return Notification::findOrFail($id);
    }

    public function create(array $data)
    {
        return Notification::create($data);
    }

    public function update($id, array $data)
    {
        $record = Notification::findOrFail($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        return Notification::destroy($id);
    }

    public static function send(User $user, string $type, string $message, $notifiable = null)
    {
        $notification = $user->notifications()->create([
            'type' => $type,
            'message' => $message,
            'notifiable_type' => $notifiable ? get_class($notifiable) : null,
            'notifiable_id' => $notifiable ? $notifiable->id : null,
            'is_read' => false,
        ]);

        // If you're using real-time notifications
        event(new NotificationSent($notification));

        return $notification;
    }
}
