<?php

namespace App\Filters;

use Carbon\Carbon;

abstract class QueryFilter
{
    protected $query;
    protected $clause;

    public function __construct($query)
    {
        $this->query = $query;
    }

    protected function extractDates($dates)
    {
        $dates = explode(',', $dates);
        $start = null;
        $end = null;

        foreach ($dates as $value) {
            if (empty($value)) {
                continue; // skip empty values
            }
            [$key, $val] = explode("|", $value);
            if ($key === "start") {
                $start = $val;
            } elseif ($key === "end") {
                $end = $val;
            }
        }
        return compact('start', 'end');
    }
}
