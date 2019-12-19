<?php

namespace PhpSchool\CliMenu\Style;

class StaticStyle implements ItemStyleInterface
{
    use ItemStyleTrait;

    protected const DEFAULT_STYLES = [
        'markerOn'      => '',
        'markerOff'     => '',
        'itemExtra'     => '',
        'displaysExtra' => false,
    ];

    public function __construct()
    {
        $this->fromArray([]);
    }

    public function setMarkerOn(string $marker) : self
    {
        return $this;
    }

    public function setMarkerOff(string $marker) : self
    {
        return $this;
    }

    public function setItemExtra(string $itemExtra) : self
    {
        return $this;
    }

    public function setDisplaysExtra(bool $displaysExtra) : self
    {
        return $this;
    }

    public function toArray(): array
    {
        return [
            'markerOn'      => $this->markerOn,
            'markerOff'     => $this->markerOff,
            'itemExtra'     => $this->itemExtra,
            'displaysExtra' => $this->displaysExtra,
        ];
    }

    public function fromArray(array $style) : self
    {
        $this->markerOn      = self::DEFAULT_STYLES['markerOn'];
        $this->markerOff     = self::DEFAULT_STYLES['markerOff'];
        $this->itemExtra     = self::DEFAULT_STYLES['itemExtra'];
        $this->displaysExtra = self::DEFAULT_STYLES['displaysExtra'];

        return $this;
    }
}
