<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Media extends BaseModel
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'original_name',
        'hash_name',
        'folder',
        'extension',
        'mimetype',
        'size',
        'attachable_type',
        'attachable_id',
        'uploaded_by_id',
        'payload'
    ];

    protected $appends = ['link', 'type', 'path', 'icon', 'is_owner', 'storage_link'];

    protected $casts = [
        'payload' => 'object'
    ];

    /**
     * @return string
     */
    public function getTypeAttribute(): string
    {
        return explode('/', $this->mimetype)[1];
    }

    /**
     * @param int|string|float $size
     *
     * @return float|string
     */
    public function getSizeAttribute($size): float|string
    {
        return formatBytes($size);
    }

    /**
     * @return string
     */
    public function getPathAttribute(): string
    {
        return $this->folder . '/' . $this->hash_name;
    }

    /**
     * @return string
     */
    public function getStorageLinkAttribute(): string
    {
        return env('APP_URL') . '/storage/' . $this->folder . '/' . $this->hash_name;
    }

    /**
     * @return string
     */
    public function getLinkAttribute(): string
    {
        return env('APP_URL') . '/' . $this->path;
    }

    /**
     * @return string
     */
    public function getIconAttribute(): string
    {
        $extension = $this->extension;
        switch ($extension) {
            case 'png':
                $icon = 'las la-file-image';
                break;
            case 'jpg':
                $icon = 'las la-file-image';
                break;
            case 'jpeg':
                $icon = 'las la-file-image';
                break;
            case 'svg':
                $icon = 'las la-file-image';
                break;
            case 'tif':
                $icon = 'las la-file-image';
                break;
            case 'xls':
                $icon = 'las la-file-excel text-office-excel';
                break;
            case 'xlsx':
                $icon = 'las la-file-excel text-office-excel';
                break;
            case 'csv':
                $icon = 'las la-file-csv text-office-excel';
                break;
            case 'doc':
                $icon = 'las la-file-word text-office-word';
                break;
            case 'docx':
                $icon = 'las la-file-word text-office-word';
                break;
            case 'pdf':
                $icon = 'las la-file-pdf text-pdf';
                break;
            default:
                $icon = 'las la-file-alt';
                break;
        }
        return $icon;
    }

    public function getIsOwnerAttribute()
    {
        return true;
        // return auth()->user()->id == $this->uploaded_by_id;
    }

    /**
     * Relationships
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function uploader(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_id');
    }
}