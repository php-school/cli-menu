<?php

namespace PhpSchool\CliMenu\Style;

class SplitStyle
{
    /**
     * @var string
     */
    protected $itemExtra;

    /**
     * @var bool
     */
    protected $displaysExtra;

    protected const DEFAULT_STYLES = [
        'itemExtra'     => '',
        'displaysExtra' => false,
    ];

    public function __construct()
    {
        $this->setItemExtra(self::DEFAULT_STYLES['itemExtra']);
        $this->setDisplaysExtra(self::DEFAULT_STYLES['displaysExtra']);
    }

    public function getItemExtra() : string
    {
        return $this->itemExtra;
    }

    public function setItemExtra(string $itemExtra) : self
    {
        $this->itemExtra = $itemExtra;

        return $this;
    }

    public function getDisplaysExtra() : bool
    {
        return $this->displaysExtra;
    }

    public function setDisplaysExtra(bool $displaysExtra) : self
    {
        $this->displaysExtra = $displaysExtra;

        return $this;
    }

    public function toArray() : array
    {
        return [
            'itemExtra'     => $this->itemExtra,
            'displaysExtra' => $this->displaysExtra,
        ];
    }

    public function fromArray(array $style) : self
    {
        $this->itemExtra     = $style['itemExtra'] ?? $this->itemExtra;
        $this->displaysExtra = $style['displaysExtra'] ?? $this->displaysExtra;

        return $this;
    }
}
