<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function getValidationRules()
    {
        return response()->json(formatForSelect(getValidationRules()));
    }
}