<?php

use Carbon\Carbon;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

if (!function_exists('search')) {
    /**
     * Search in query builder
     *
     * @param Builder $query
     * @param string|null $value
     *
     * @return Illuminate\Contracts\Database\Query\Builder
     */
    function search(Builder $query, ?string $value): Builder
    {
        $columns = $query->columns;
        $query = $query->where(function ($query) use ($columns, $value) {
            $i = 0;
            foreach ($columns as $key => $column) {
                $field = $column;
                $isAlias = false;
                // Check if the column has an alias
                if (str_contains($column, 'as')) {
                    $isAlias = true;
                    [$field, $alias] = explode(' as ', strtolower($column));
                    $field = trim($field);
                }
                if ($field !== 'password' && !str_contains($field, 'id')) {
                    if ($isAlias) {
                        $clause = 'having';
                    } else {
                        $clause = $i > 0 ? 'orWhere' : 'where';
                    }
                    $query = $query->$clause($field, 'LIKE', '%' . $value . '%');
                }
                $i += 1;
            }
            return $query;
        });
        return $query;
    }
}

if (!function_exists('orderByMultiple')) {
    /**
     * Order by multiple columns in query builder
     *
     * @param Builder $query
     * @param string|null $value
     *
     * @return Illuminate\Contracts\Database\Query\Builder
     */
    function orderByMultiple(Builder $query, ?array $data): Builder
    {
        foreach ($data as $column => $direction) {
            $query = $query->orderBy($column, $direction);
        }
        return $query;
    }
}

if (!function_exists('getMedia')) {
    function getMedia(string|array $mediaIds = null)
    {
        $media = DB::table('media', 'm')->select([
            'm.id', 'm.original_name', 'm.hash_name', 'm.folder', 'm.extension', 'm.mimetype', DB::raw('CAST(m.size as int) AS size'), 'm.payload', 'hm.attachable_type', 'hm.attachable_id', 'm.uploaded_by_id', 'm.created_at', 'm.payload',
            'u.username', 'u.first_name', 'u.last_name'
        ]);


        if ($mediaIds) {
            $mediaIds = gettype($mediaIds) == 'string' ? explode(',', $mediaIds) : $mediaIds;
            $media = $media->whereIn('m.id', $mediaIds);
        }

        $media = $media->leftJoin('has_media as hm', 'hm.media_id', 'm.id')
            ->leftJoin('users as u', 'u.id', 'uploaded_by_id')
            ->orderBy('size', 'DESC');

        return $media;
    }
}

if (!function_exists('getSingleMedia')) {
    function getSingleMedia(string $id)
    {
        $media = getMedia($id)->first();
        $media->size_str = formatBytes($media->size);
        $media->type = getMediaType($media->attachable_type, $media->folder);
        $media->created_at_formatted = Carbon::parse($media->created_at)->diffForHumans();
        $media->full_name = $media->first_name && $media->last_name ? ucfirst(strtolower($media->first_name)) . ' ' . ucfirst(strtolower($media->last_name)) : 'Système';
        $media->path = $media->folder . '/' . $media->hash_name;
        $media->link = getMediaLink($media->folder, $media->hash_name);
        $media->icon = getMediaIcon($media);
        $media->storage_link = getMediaStorageLink($media->folder, $media->hash_name);
        $media->is_owner = true;
        return $media;
    }
}
