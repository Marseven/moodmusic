<?php
/**
 * Modified from https://github.com/aerni/laravel-spotify
 * Copyright by aerni
 * MIT License
 */

namespace App\Modules\Spotify;

class Normalizer
{
    /**
     * Normalize the provided argument.
     *
     * @param $argument
     * @return string
     */
    public static function normalizeArgument($argument): string
    {
        if (is_array($argument)) {
            $argument = collect($argument)->implode(',');
        } elseif (is_string($argument)) {
            $argument = str_replace(' ', '', $argument);
        }

        return $argument;
    }
}
