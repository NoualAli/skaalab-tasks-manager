<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Jenssegers\Agent\Agent;


class Login extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'last_activity',
    ];

    protected $agent;

    public function __construct()
    {
        $this->agent = new Agent;
        $this->agent->setUserAgent($this->user_agent);
    }

    public function getLanguagesAttribute()
    {
        $languages = $this->agent->languages();
        return !empty($languages) && $languages !== null ? implode(', ', $languages) : null;
    }

    public function getRobotAttribute()
    {
        return $this->agent->robot();
    }

    public function getDeviceAttribute()
    {
        return $this->agent->device();
    }

    public function getBrowserAttribute()
    {
        return $this->agent->browser();
    }

    public function getBrowserVersionAttribute()
    {
        return $this->agent->version($this->browser);
    }

    public function getPlatformAttribute()
    {
        return $this->agent->platform();
    }

    public function getPlatformVersionAttribute()
    {
        return $this->agent->version($this->platform);
    }

    public function getLastActivityAttribute($last_activity)
    {
        return !empty($last_activity) && $last_activity !== null ? Carbon::parse($last_activity)->diffForHumans() : Carbon::parse($this->created_at)->format('d-m-Y H:i:s');
    }
}
