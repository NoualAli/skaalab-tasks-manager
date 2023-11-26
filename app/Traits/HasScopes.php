<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait HasScopes
{
    /**
     * Récupère les valeurs unique des relation liés au model
     *
     * @param Builder $query
     * @param string $related
     * @param string $dbAttribute
     * @param string $key
     * @param string|null $valueToShow
     *
     * @return array
     */
    public function scopeRelationUniqueData(Builder $query, string $related, string $dbAttribute = 'name', string $key = 'id', ?string $valueToShow = null): array
    {
        $valueToShow = $valueToShow ?? $dbAttribute;
        $data = $query->with($related)->get()->map(function ($item) use ($related, $valueToShow, $key) {
            if ($item->$related instanceof \Illuminate\Database\Eloquent\Collection) {
                return $item->$related->map(fn ($item) => ['id' => optional($item)->$key, 'label' => optional($item)->$valueToShow])->collapse();
            }
            return ['id' => optional($item->$related)->$key, 'label' => optional($item->$related)->$valueToShow];
        })->filter(function ($item) {
            return $item;
        })->unique()->sort()->values()->toArray();
        return $data;
    }

    /**
     * Récupère les valeurs unique dans le même modèle
     *
     * @param Builder $query
     * @param string $attribute
     *
     * @return array
     */
    public function scopeUniqueData(Builder $query, string $attribute): array
    {
        $query = $query->groupBy($attribute)->orderBy($attribute)->select($attribute)->get()->pluck($attribute)->map(function ($item) {
            return trim($item);
        })->filter(function ($key, $value) {
            return !empty($key) && $value !== ' ' && !is_null($value);
        })->toArray();

        return array_combine(array_values($query), array_values($query));
    }

    /**
     * Get rows between two dates
     *
     * @param Builder $query
     * @param string|null $start
     * @param string|null $end
     * @param string $startField
     * @param string $endField
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeBetween(Builder $query, string $start = null, string $end = null, string $startField = 'start', string $endField = 'end'): Builder
    {
        $start = is_null($start) ? today()->format('Y-m-d') : Carbon::parse($start)->format('Y-m-d');
        $end = is_null($end) ? Carbon::parse($start)->addDay()->format('Y-m-d') : Carbon::parse($end)->addDay()->format('Y-m-d');

        return $query->whereNotNull($startField)
            ->whereNotNull($endField)
            ->where(function ($q) use ($startField, $endField, $start, $end) {
                $q->where(function ($q) use ($startField, $start, $end) {
                    $q->whereNull($startField)
                        ->orWhere($startField, '<=', $end);
                })->where(function ($q) use ($endField, $start, $end) {
                    $q->whereNull($endField)
                        ->orWhere($endField, '>=', $start);
                });
            });
    }
}
