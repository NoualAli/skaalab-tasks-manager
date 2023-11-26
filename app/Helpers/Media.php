<?php

if (!function_exists('getMediaIcon')) {
    /**
     * @return string
     */
    function getMediaIcon($file): string
    {
        $extension = strtolower($file->extension);
        $icon = 'las la-file-alt';

        if (in_array($extension, ['jpg', 'jpeg', 'png', 'svg', 'tif'])) {
            $icon = 'las la-file-image';
        } elseif (in_array($extension, ['xls', 'xlsx'])) {
            $icon = 'las la-file-excel text-office-excel';
        } elseif ($extension == 'csv') {
            $icon = 'las la-file-csv text-office-excel';
        } elseif (in_array($extension, ['doc', 'docx'])) {
            $icon = 'las la-file-word text-office-word';
        } elseif ($extension == 'pdf') {
            $icon = 'las la-file-pdf text-pdf';
        }
        return $icon;
    }
}

if (!function_exists('getMediaType')) {
    /**
     * @param string $attachableType
     * @param string $folder
     *
     * @return string
     */
    function getMediaType(string $attachableType, string $folder): string
    {
        return 'Inconnu';
    }
}

if (!function_exists('getMediaLink')) {
    /**
     * @param string $folder
     * @param string $hash_name
     *
     * @return string
     */
    function getMediaLink(string $folder, string $hash_name): string
    {
        return env('APP_URL') . '/' . $folder . '/' . $hash_name;
    }
}


if (!function_exists('getMediaStorageLink')) {
    /**
     * @param string $folder
     * @param string $hash_name
     *
     * @return string
     */
    function getMediaStorageLink(string $folder, string $hash_name): string
    {
        return env('APP_URL') . '/storage/' . $folder . '/' . $hash_name;
    }
}
