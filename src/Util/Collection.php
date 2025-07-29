<?php

declare(strict_types=1);

namespace PhpSchool\CliMenu\Util;

/**
 * @template TKey of array-key
 * @template TItem of mixed
 */
class Collection
{
    /**
     * @var array<TKey, TItem>
     */
    private array $items;

    /**
     * @param array<TKey, TItem> $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @template TItemNew of mixed
 *
     * @param callable(TKey, TItem): TItemNew $cb
     * @return self<TKey, TItemNew>
     */
    public function map(callable $cb): self
    {
        return new self(mapWithKeys($this->items, $cb));
    }

    /**
     * @return self<TKey, TItem>
     */
    public function filter(callable $cb): self
    {
        return new self(filter($this->items, $cb));
    }

    /**
     * @return self<int, TItem>
     */
    public function values(): self
    {
        return new self(array_values($this->items));
    }

    /**
     * @return self<TKey, TItem>
     */
    public function each(callable $cb): self
    {
        each($this->items, $cb);

        return $this;
    }

    /**
     * @return array<TKey, TItem>
     */
    public function all(): array
    {
        return $this->items;
    }
}
