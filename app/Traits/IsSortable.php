<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;

trait IsSortable
{
    public function scopeSortByMultiple($query, $sortArray)
    {
        foreach ($sortArray as $key => $value) {
            $key = str_contains($key, '.') ? explode('.', $key) : $key;

            if (is_array($key)) {
                $relatedModel = $key[0];
                $className = $this->getNormalizedName($relatedModel);
                $class = 'App\Models\\' . $className;
                $field = $key[1];
                if (!class_exists($class)) {
                    continue;
                } else {
                    $relatedTable = (new $class)->getTable();
                    $currentModel = $query->getModel();
                    $currentTable = $currentModel->getTable();
                    $query = $query->orderBy($class::select($field)->limit(1));
                }
            } else {
                $query = $query->orderBy($key, $value);
            }
        }
        return $query;
    }

    /**
     * Normalize model name
     *
     * @param string|null $name
     *
     * @return string
     */
    private function getNormalizedName(?string $name): string
    {
        $normailizedName = preg_replace('/_/', ' ', $name);
        $normailizedName = preg_split('/[ ]/', ucwords($normailizedName));
        $normailizedName = implode('', $normailizedName);
        return $normailizedName;
    }
}
