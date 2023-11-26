<?php

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

if (!function_exists('isRoot')) {
    function isRoot()
    {
        return hasRole('root');
    }
}

if (!function_exists('isAbleTo')) {
    /**
     * Check if user has specific abilities
     * @param string|null $user
     * @param string|array $abilities
     *
     * @return bool
     */
    function isAbleTo(string|array $abilities): bool
    {
        $user = auth()->user();
        return $user->isAbleTo($abilities);
    }
}

if (!function_exists('isAbleOrAbort')) {
    /**
     * Check if user has specific abilities or abort
     *
     * @param string|array $abilities
     *
     * @return void
     */
    function isAbleOrAbort(string|array $abilities)
    {
        return abort_if(!isAbleTo($abilities), 403, 'Vous n\'êtes pas autoriser à accéder à cette resource');
    }
}

if (!function_exists('hasRole')) {
    /**
     * Check if user has specific role
     *
     * @param string|array $roles
     *
     * @return bool
     */
    function hasRole(string|array $roles, ?User $user = null): bool
    {
        $user = $user ? $user : auth()->user();
        $role = $user->role->code;
        if (is_array($roles)) {
            return in_array($role, $roles);
        } else {
            return $role == $roles;
        }
    }
}

if (!function_exists('hasDre')) {
    /**
     * Check if user has specific dre
     *
     * @param string|array $dres
     *
     * @return bool
     */
    function hasDre(string|array $dres): bool
    {
        $user = auth()->user();
        $userDres = $user->dres->pluck('name')->toArray();
        if (is_array($dres)) {
            $hasDre = [];
            foreach ($dres as $dre) {
                array_push($hasDre, in_array($dre, $userDres));
            }
            return in_array(true, $hasDre);
        } else {
            return in_array($dres, $userDres);
        }
    }
}

if (!function_exists('hasRoleOrAbort')) {
    /**
     * Check if user has specific role with throw exception
     *
     * @param string|array $roles
     *
     * @return void
     */
    function hasRoleOrAbort(string|array $roles): void
    {
        abort_if(!hasRole($roles), 401, __('Whoops! You are not authorized to access this resource'));
    }
}

if (!function_exists('onlyRoot')) {
    function onlyRoot()
    {
        return hasRoleOrAbort('root');
    }
}


if (!function_exists('addZero')) {
    /**
     * Add 0 if number lower than 10
     *
     * @param string|integer $num
     *
     * @return string
     */
    function addZero($num)
    {
        return $num < 10 ? '0' . $num : $num;
    }
}

if (!function_exists('formatForSelect')) {
    /**
     * Formats a data table to make it usable directly at the <select> level
     *
     * @param array $array
     * @param string $label
     * @param string $id
     *
     * @return array
     */
    function formatForSelect(array $array = [], string $label = 'name', string $id = 'id'): array
    {
        if (!empty($array)) {
            $array = array_map(function ($item) use ($label, $id) {

                $id = isset($item[$id]) ? $item[$id] : $item;

                $label = isset($item[$label]) ? $item[$label] : $item;
                return [
                    'id' => $id,
                    'label' => $label,
                ];
            }, $array, $array);
        }
        return $array;
    }
}

if (!function_exists('paginate')) {

    /**
     * Wrapper to paginate data with LengthAwarePaginator
     *
     * @param Object $data
     * @param string $url
     * @param int $perPage
     *
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    function paginate(Object $data, string $url, int $perPage = 10)
    {
        $data = collect($data);
        $url = env('APP_URL') . $url;
        $page = request()->has('page') ? request()->page : 1;
        return (new LengthAwarePaginator($data->forPage($page, $perPage), $data->count(), $perPage))->setPath($url)->onEachSide(1);
    }
}

if (!function_exists('formatBytes')) {
    /**
     * Convet bytes to KB, MB, GB, TB
     *
     * @param int $size
     * @param bool $withSuffix
     * @param int $precision
     *
     * @return float|string
     */
    function formatBytes(int $size, bool $withSuffix = true, int $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');
        $result = round(pow(1024, $base - floor($base)), $precision);
        return $withSuffix ? $result . ' ' . $suffixes[floor($base)] : $result;
    }
}

if (!function_exists('recursive_collect')) {
    function recursive_collect(array $array)
    {
        return collect($array)->map(function ($item) {
            if (is_array($item)) {
                return recursive_collect($item);
            }
            return $item;
        });
    }
}

if (!function_exists('recursivelyToArray')) {
    function recursivelyToArray($collection)
    {
        return $collection->map(function ($item) {
            if ($item instanceof Illuminate\Support\Collection) {
                $item = is_integer($item->keys()->first()) ?  recursivelyToArray($item->values()) : recursivelyToArray($item);
            } elseif (is_array($item)) {
                $item = recursivelyToArray(collect($item));
            } elseif ($item instanceof stdClass) {
                $item = json_decode(json_encode($item), true);
            }

            return $item;
        })->toArray();
    }
}
