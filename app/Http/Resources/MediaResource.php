<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
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
            'original_name' => $this->original_name,
            'folder' => $this->folder,
            'mimetype' => $this->mimetype,
            'extension' => $this->extension,
            'size_str' => formatBytes($this->size),
            'payload' => $this->payload,
            'type' => getMediaType($this->attachable_type, $this->folder),
            'created_at_formatted' => Carbon::parse($this->created_at)->diffForHumans(),
            'full_name' => $this->first_name && $this->last_name ? ucfirst(strtolower($this->first_name)) . ' ' . ucfirst(strtolower($this->last_name)) : 'Système',
            'mimetype' => $this->mimetype,
            'path' => $this->folder . '/' . $this->hash_name,
            'link' => getMediaLink($this->folder, $this->hash_name),
            'icon' => getMediaIcon($this),
            'storage_link' => getMediaStorageLink($this->folder, $this->hash_name),
            'is_owner' => true,
        ];
    }
}