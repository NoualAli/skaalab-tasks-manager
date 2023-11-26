<?php

namespace App\Traits;

use App\Filters\FilterBuilder;

trait IsFilterable
{
    /**
     * Filter namespace
     *
     * @var string
     */
    protected $filter_namespace = 'App\Filters';

    public function scopeFilter($query, array $filters)
    {
        $namespace = $this->filter ?? $this->filter_namespace;
        $filter = new FilterBuilder($query, $filters, $namespace);

        return $filter->apply();
    }
}