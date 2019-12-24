<?php

declare(strict_types=1);

namespace PhpSchool\CliMenu\Util;

class ArrayUtil
{
    public static function mapWithKeys(array $array, callable $callback) : array
    {
        return array_combine(
            array_keys($array),
            array_map($callback, array_keys($array), $array)
        );
    }
}
