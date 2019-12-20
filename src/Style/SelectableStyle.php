<?php

namespace PhpSchool\CliMenu\Style;

class SelectableStyle
{
    private const DEFAULT_STYLES = [
        'selectedMarker'   => '● ',
        'unselectedMarker' => '○ ',
        'itemExtra'        => '✔',
        'displaysExtra'    => false,
    ];

    /**
     * @var string
     */
    private $selectedMarker;

    /**
     * @var string
     */
    private $unselectedMarker;

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
    private $custom = false;

    public function __construct()
    {
        $this->selectedMarker   = self::DEFAULT_STYLES['selectedMarker'];
        $this->unselectedMarker = self::DEFAULT_STYLES['unselectedMarker'];
        $this->itemExtra        = self::DEFAULT_STYLES['itemExtra'];
        $this->displaysExtra    = self::DEFAULT_STYLES['displaysExtra'];
    }

    public function hasChangedFromDefaults() : bool
    {
        return $this->custom;
    }

    public function getMarker(bool $selected) : string
    {
        return $selected ? $this->selectedMarker : $this->unselectedMarker;
    }

    public function getSelectedMarker() : string
    {
        return $this->selectedMarker;
    }

    public function setSelectedMarker(string $marker) : self
    {
        $this->custom = true;

        $this->selectedMarker = $marker;

        return $this;
    }

    public function getUnselectedMarker() : string
    {
        return $this->unselectedMarker;
    }

    public function setUnselectedMarker(string $marker) : self
    {
        $this->custom = true;

        $this->unselectedMarker = $marker;

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
