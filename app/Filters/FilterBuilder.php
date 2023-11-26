<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class FilterBuilder
{
    /**
     * @var Builder
     */
    protected $query;
    /**
     * @var array
     */
    protected $filters;
    /**
     * @var string
     */
    protected $namespace;
    /**
     * @var string
     */
    protected $global_namespace = 'App\Filters';

    /**
     * @param Builder $query
     * @param array $filters
     * @param string $namespace
     */
    public function __construct(Builder $query, array $filters, string $namespace = "App\Filters")
    {
        $this->query = $query;
        $this->filters = $filters;
        $this->namespace = $namespace;
    }

    /**
     * Applique le filtre adéquat
     * @return Builder
     */
    public function apply(): Builder
    {
        foreach ($this->filters as $name => $value) {
            $normailizedName = $this->getNormalizedName($name);
            $class = $this->namespace . "\\{$normailizedName}";
            if (!class_exists($class)) {
                $class = $this->global_namespace . "\\{$normailizedName}";
                if (!class_exists($class)) {
                    continue;
                }
            }
            if (strlen($value)) {
                $value = strtolower($value) == 'null' ? null : $value;
                (new $class($this->query))->handle($value);
            }
        }

        return $this->query;
    }

    /**
     * Normalise le nom du filtre pour trouver la class adéquate
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