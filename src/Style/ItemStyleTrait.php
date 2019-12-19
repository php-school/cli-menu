<?php

namespace PhpSchool\CliMenu\Style;

trait ItemStyleTrait
{
    /**
     * @var string
     */
    protected $markerOn;

    /**
     * @var string
     */
    protected $markerOff;

    /**
     * @var string
     */
    protected $itemExtra;

    /**
     * @var bool
     */
    protected $displaysExtra;

    protected $custom = false;

    public function getIsCustom() : bool
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
