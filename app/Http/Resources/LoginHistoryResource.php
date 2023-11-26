<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'language' => $this->language,
            'robot' => $this->robot,
            'device' => $this->device,
            'browser' => $this->browser,
            'browser_version' => $this->browser_version,
            'platform' => $this->platform,
            'platform_version' => $this->platform_version,
            'last_activity' => $this->last_activity,
            'ip_address' => $this->ip_address,
        ];
    }
}
