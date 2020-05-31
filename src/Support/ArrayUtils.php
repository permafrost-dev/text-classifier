<?php

namespace Permafrost\TextClassifier\Support;

class ArrayUtils
{
    /**
     * Flatten a multi-dimensional array into a single level.
     * Taken from Laravel 7.x class Illuminate\Support\Arr.
     *
     * @param array $array
     * @param int   $depth
     *
     * @return array
     */
    public static function flatten($array, $depth = INF): array
    {
        $result = [];

        foreach ($array as $item) {
            if (!is_array($item)) {
                $result[] = $item;
            } else {
                $values = $depth === 1 ? array_values($item) : static::flatten($item, $depth - 1);
                foreach ($values as $value) {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }
}
