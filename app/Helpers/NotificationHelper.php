<?php

namespace App\Helpers;

use App\Models\Notification;

class NotificationHelper
{
    /**
     * Create a notification.
     */
    public static function create(
        string $title,
        string $message,
        string $type = 'info',
        ?string $role = null,
        ?int $medicineId = null,
        ?int $userId = null
    ): Notification {

        return Notification::create([

            'title'       => $title,

            'message'     => $message,

            'type'        => $type,

            'is_read'     => false,

            'role'        => $role,

            'medicine_id' => $medicineId,

            'user_id'     => $userId,

        ]);

    }
}