<?php

declare(strict_types=1);

namespace PhpSchool\CliMenu\Util;

function mapWithKeys(array $array, callable $callback) : array
{
    return array_combine(
        array_keys($array),
        array_map($callback, array_keys($array), $array)
    );
}

function each(array $array, callable $callback) : void
{
    foreach ($array as $k => $v) {
        $callback($k, $v);
    }
}
