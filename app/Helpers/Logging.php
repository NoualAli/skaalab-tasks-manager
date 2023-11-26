<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('log_error')) {
    function log_error($message)
    {
        $user = auth()->user()->id . ' - ' . auth()->user()->full_name;
        Log::error("$user " . $message);
    }
}

if (!function_exists('log_warning')) {
    function log_warning($message)
    {
        $user = auth()->user()->id . ' - ' . auth()->user()->full_name;
        Log::warning("$user " . $message);
    }
}

if (!function_exists('log_info')) {
    function log_info($message)
    {
        Log::info($message);
    }
}