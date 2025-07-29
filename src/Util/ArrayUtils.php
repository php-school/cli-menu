<?php

declare(strict_types=1);

namespace PhpSchool\CliMenu\Util;

/**
 * @template TKey of array-key
 * @template TItem
 * @template TItemNew
 *
 * @param array<TKey, TItem> $array
 * @param callable(TKey, TItem): TItemNew $callback
 * @return array<TKey, TItemNew>
 */
function mapWithKeys(array $array, callable $callback) : array
{
    return array_combine(
        array_keys($array),
        array_map($callback, array_keys($array), $array)
    );
}

/**
 * @template TKey of array-key
 * @template TItem
 *
 * @param array<TKey, TItem> $array
 * @param callable(TKey, TItem): bool $callback
 * @return array<TKey, TItem>
 */
function filter(array $array, callable $callback) : array
{
    return array_filter($array, function ($v, $k) use ($callback) {
        return $callback($k, $v);
    }, ARRAY_FILTER_USE_BOTH);
}

/**
 * @template TKey of array-key
 * @template TItem
 *
 * @param array<TKey, TItem> $array
 * @param callable(TKey, TItem): void $callback
 */
function each(array $array, callable $callback) : void
{
    foreach ($array as $k => $v) {
        $callback($k, $v);
    }
}

/**
 * @param list<int> $items
 */
function max(array $items) : int
{
    return count($items) > 0 ? \max($items) : 0;
}

/**
 * @template TKey of array-key
 * @template TItem of mixed
 *
 * @param array<TKey, TItem> $items
 * @return Collection<TKey, TItem>
 */
function collect(array $items) : Collection
{
    return new Collection($items);
}
