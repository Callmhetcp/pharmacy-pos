<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityHelper
{
    public static function log($action, $module, $description)
    {
        ActivityLog::create([
            'user_id'     => Auth::check() ? Auth::id() : null,
            'action'      => $action,
            'module'      => $module,
            'description' => $description,
            'ip_address'  => request()->ip(),
            'browser'     => request()->userAgent(),
        ]);
    }
}