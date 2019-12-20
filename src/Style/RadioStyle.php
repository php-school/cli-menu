<?php

namespace PhpSchool\CliMenu\Style;

class RadioStyle
{
    private const DEFAULT_STYLES = [
        'markerOn'      => '[●] ',
        'markerOff'     => '[○] ',
        'itemExtra'     => '✔',
        'displaysExtra' => false,
    ];

    /**
     * @var string
     */
    private $markerOn;

    /**
     * @var string
     */
    private $markerOff;

    /**
     * @var string
     */
    private $itemExtra;

    /**
     * @var bool
     */
    private $displaysExtra;

    /**
     * @var bool
     */
    protected $custom = false;

    public function __construct()
    {
        $this->markerOn      = self::DEFAULT_STYLES['markerOn'];
        $this->markerOff     = self::DEFAULT_STYLES['markerOff'];
        $this->itemExtra     = self::DEFAULT_STYLES['itemExtra'];
        $this->displaysExtra = self::DEFAULT_STYLES['displaysExtra'];
    }

    public function hasChangedFromDefaults() : bool
    {
        $currentValues = [
            $this->markerOn,
            $this->markerOff,
            $this->itemExtra,
            $this->displaysExtra,
        ];

        return $currentValues !== array_values(self::DEFAULT_STYLES);
    }

    public function getMarker(bool $selected) : string
    {
        return $selected ? $this->markerOn : $this->markerOff;
    }

    public function getMarkerOn() : string
    {
        return $this->markerOn;
    }

    public function setMarkerOn(string $marker) : self
    {
        $this->markerOn = $marker;

        return $this;
    }

    public function getMarkerOff() : string
    {
        return $this->markerOff;
    }

    public function setMarkerOff(string $marker) : self
    {
        $this->markerOff = $marker;

        return $this;
    }

    public function getItemExtra() : string
    {
        return $this->itemExtra;
    }

    public function setItemExtra(string $itemExtra) : self
    {
        $this->itemExtra = $itemExtra;

        // if we customise item extra, it means we most likely want to display it
        $this->setDisplaysExtra(true);

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
}
