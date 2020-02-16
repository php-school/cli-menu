<?php

declare(strict_types=1);

namespace PhpSchool\CliMenu\Util;

class Collection
{
    /**
     * @var array
     */
    private $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function map(callable $cb) : self
    {
        return new self(mapWithKeys($this->items, $cb));
    }

    public function filter(callable $cb) : self
    {
        return new self(filter($this->items, $cb));
    }

    public function values() : self
    {
        return new self(array_values($this->items));
    }

    public function each(callable $cb) : self
    {
        each($this->items, $cb);

        return $this;
    }

    public function all() : array
    {
        return $this->items;
    }
}
