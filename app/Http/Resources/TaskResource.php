<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'deadline' => $this->deadline_formatted,
            'priority' => $this->priority_str,
            'is_resolved' => $this->is_resolved,
            'created_by_id' => $this->created_by_id,
            'assigned_to_id' => $this->assigned_to_id,
            // 'excuter_name' => $this->executer?->full_name
        ];
    }
}
