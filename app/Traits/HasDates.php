<?php

namespace App\Traits;

use Carbon\Carbon;

trait HasDates
{
    /**
     * @param mixed $created_at
     *
     * @return string
     */
    public function getCreatedAtAttribute($created_at): string
    {
        return Carbon::parse($created_at)->format('d-m-Y');
    }

    /**
     * @param mixed $updated_at
     *
     * @return string
     */
    public function getUpdatedAtAttribute($updated_at): string
    {
        return Carbon::parse($updated_at)->format('d-m-Y');
    }

    /**
     * @param mixed $deleted_at
     *
     * @return string
     */
    public function getDeletedAtAttribute($deleted_at): string
    {
        return Carbon::parse($deleted_at)->format('d-m-Y');
    }

    /**
     * @param mixed $start
     *
     * @return string
     */
    public function getStartAttribute($start): string
    {
        return $start ? Carbon::parse($start)->format('d-m-Y') : '';
    }

    /**
     * @param mixed $end
     *
     * @return string
     */
    public function getEndAttribute($end): string
    {
        return $end ? Carbon::parse($end)->format('d-m-Y') : '';
    }

    /**
     * @return int
     */
    public function getRemainingDaysBeforeStartAttribute(): int
    {
        $today = today();
        $startAttribute = $this->startAttribute ?? 'start';
        $start = $this->$startAttribute ? $today->diffInDays($this->$startAttribute, false) : 0;
        return $start >= 0 ? $start : 0;
    }

    /**
     * @return int
     */
    public function getRemainingDaysBeforeEndAttribute(): int
    {
        $today = today();
        $endAttribute = $this->endAttribute ?? 'end';
        $end = $this->$endAttribute ? $today->diffInDays($this->$endAttribute, false) : 0;
        return $end >= 0 ? $end : 0;
    }

    /**
     * @return string
     */
    public function getRemainingDaysBeforeStartStrAttribute(): string
    {
        $remainingDays = $this->remaining_days_before_start > 1 ? $this->remaining_days_before_start . ' jours' : $this->remaining_days_before_start . ' jour';
        if (!$this->remaining_days_before_start && $this->remaining_days_before_end) {
            return 'En cours';
        }
        return $this->remaining_days_before_end ? $remainingDays : '-';
    }

    /**
     * @return string
     */
    public function getRemainingDaysBeforeEndStrAttribute(): string
    {
        $remainingDays = $this->remaining_days_before_end > 1 ? $this->remaining_days_before_end . ' jours' : $this->remaining_days_before_end . ' jour';
        return $this->remaining_days_before_end ? $remainingDays : '-';
    }
}
