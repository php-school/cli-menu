<?php

declare(strict_types=1);

namespace PhpSchool\CliMenu\Util;

function mapWithKeys(array $array, callable $callback) : array
{
    $arr = array_combine(
        array_keys($array),
        array_map($callback, array_keys($array), $array)
    );

    assert(is_array($arr));

    return $arr;
}

function filter(array $array, callable $callback) : array
{
    return array_filter($array, function ($v, $k) use ($callback) {
        return $callback($k, $v);
    }, ARRAY_FILTER_USE_BOTH);
}

function each(array $array, callable $callback) : void
{
    foreach ($array as $k => $v) {
        $callback($k, $v);
    }
}

function max(array $items) : int
{
    return count($items) > 0 ? \max($items) : 0;
}

function collect(array $items) : Collection
{
    return new Collection($items);
}
