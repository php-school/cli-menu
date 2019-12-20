<?php

namespace PhpSchool\CliMenu\Style;

class CheckboxStyle
{
    protected const DEFAULT_STYLES = [
        'markerOn'      => '[✔] ',
        'markerOff'     => '[ ] ',
        'itemExtra'     => '✔',
        'displaysExtra' => false,
    ];

    protected $markerOn = '';

    protected $markerOff = '';

    protected $itemExtra = '';

    protected $displaysExtra = false;

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
        return $this->custom;
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
        $this->custom = true;

        $this->markerOn = $marker;

        return $this;
    }

    public function getMarkerOff() : string
    {
        return $this->markerOff;
    }

    public function setMarkerOff(string $marker) : self
    {
        $this->custom = true;

        $this->markerOff = $marker;

        return $this;
    }

    public function getItemExtra() : string
    {
        return $this->itemExtra;
    }

    public function setItemExtra(string $itemExtra) : self
    {
        $this->custom = true;

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
        $this->custom = true;

        $this->displaysExtra = $displaysExtra;

        return $this;
    }
}
