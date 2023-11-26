<?php

namespace App\Models;

use App\Traits\IsSearchable;
use App\Traits\IsSortable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory, IsSortable, IsSearchable;

    protected $fillable = [
        'title',
        'priority',
        'content',
        'deadline',
        'assigned_to_id',
        'created_by_id',
        'validated_by_id',
        'finished_at'
    ];

    protected $searchable = ['id', 'title'];

    public $appends = ['priority_str', 'deadline_formatted', 'is_resolved', 'finished_at_formatted', 'creator_name', 'executer_name', 'validator_name'];

    public function getValidatorNameAttribute(): string|null
    {
        return $this->validator?->full_name;
    }

    public function getCreatorNameAttribute(): string
    {
        return $this->creator->full_name;
    }

    public function getExecuterNameAttribute(): string|null
    {
        return $this->executer?->full_name;
    }

    public function getIsResolvedAttribute(): bool
    {
        return (bool) $this->finished_at;
    }

    public function getFinishedAtFormattedAttribute(): string
    {
        return $this->finished_at ? Carbon::parse($this->finished_at)->format('d-m-Y') : '-';
    }

    public function getDeadlineFormattedAttribute(): string
    {
        return $this->deadline ? Carbon::parse($this->deadline)->format('d-m-Y') : '-';
    }

    public function getPriorityStrAttribute(): string
    {
        $priority = $this->priority;

        switch ($priority) {
            case 1:
                return 'Normale';
                break;
            case 2:
                return 'Moyenne';
                break;
            case 3:
                return 'Urgente';
                break;
            default:
                return 'Normale';
                break;
        }
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function executer()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by_id');
    }
}
