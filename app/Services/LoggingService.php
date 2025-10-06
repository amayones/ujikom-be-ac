<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LoggingService
{
    public static function logUserAction($action, $userId, $details = [])
    {
        Log::info('User Action', [
            'action' => $action,
            'user_id' => $userId,
            'details' => $details,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()
        ]);
    }

    public static function logSecurityEvent($event, $details = [])
    {
        Log::warning('Security Event', [
            'event' => $event,
            'details' => $details,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()
        ]);
    }

    public static function logBookingEvent($event, $orderId, $details = [])
    {
        Log::info('Booking Event', [
            'event' => $event,
            'order_id' => $orderId,
            'details' => $details,
            'user_id' => auth()->id(),
            'timestamp' => now()
        ]);
    }

    public static function logError($error, $context = [])
    {
        Log::error('Application Error', [
            'error' => $error->getMessage(),
            'file' => $error->getFile(),
            'line' => $error->getLine(),
            'context' => $context,
            'timestamp' => now()
        ]);
    }
}