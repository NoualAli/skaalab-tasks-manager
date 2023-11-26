<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $createdAt = $this->created_at ? Carbon::parse($this->created_at)->diffForHumans() : '-';
        $readAt = $this->read_at ? Carbon::parse($this->read_at)->diffForHumans() : null;
        $short_content = isset($this->data['short_content']) ? $this->data['short_content'] : $this->data['content'];
        $title = isset($this->data['title']) ? $this->data['title'] : '-';
        $routeName = isset($this->data['routeName']) ? $this->data['routeName'] : '-';
        $paramNames = isset($this->data['paramNames']) ? $this->data['paramNames'] : '-';
        $modelId = isset($this->data['id']) ? $this->data['id'] : '-';
        $url = isset($this->data['url']) ? $this->data['url'] : null;
        $emitted_by = isset($this->data['emitted_by']) ? $this->data['emitted_by'] : '-';
        return [
            'id' => $this->id,
            'short_content' => $short_content,
            'title' => $title,
            'created_at' => $createdAt,
            'read_at' => $readAt,
            'routeName' => $routeName,
            'paramNames' => $paramNames,
            'modelId' => $modelId,
            'url' => $url,
            'emitted_by' => $emitted_by,
        ];
    }
}
