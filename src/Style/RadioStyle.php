<?php

namespace PhpSchool\CliMenu\Style;

class RadioStyle implements ItemStyleInterface
{
    use ItemStyleTrait;

    protected const DEFAULT_STYLES = [
        'markerOn'      => '[●] ',
        'markerOff'     => '[○] ',
        'itemExtra'     => '✔',
        'displaysExtra' => false,
    ];

    public function __construct()
    {
        $this->setMarkerOn(self::DEFAULT_STYLES['markerOn']);
        $this->setMarkerOff(self::DEFAULT_STYLES['markerOff']);
        $this->setItemExtra(self::DEFAULT_STYLES['itemExtra']);
        $this->setDisplaysExtra(self::DEFAULT_STYLES['displaysExtra']);

        $this->custom = false;
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
        $this->markerOn      = $style['markerOn'] ?? $this->markerOn;
        $this->markerOff     = $style['markerOff'] ?? $this->markerOff;
        $this->itemExtra     = $style['itemExtra'] ?? $this->itemExtra;
        $this->displaysExtra = $style['displaysExtra'] ?? $this->displaysExtra;

        return $this;
    }
}
