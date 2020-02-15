<?php

namespace PhpSchool\CliMenu\Style;

use PhpSchool\CliMenu\MenuItem\MenuItemInterface;

class SelectableStyle implements ItemStyle
{
    private const DEFAULT_STYLES = [
        'selectedMarker' => '● ',
        'unselectedMarker' => '○ ',
        'itemExtra' => '✔',
        'displaysExtra' => false,
        'paddingLeft' => 0,
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
     * @var int
     */
    private $paddingLeft;

    public function __construct()
    {
        $this->selectedMarker = self::DEFAULT_STYLES['selectedMarker'];
        $this->unselectedMarker = self::DEFAULT_STYLES['unselectedMarker'];
        $this->itemExtra = self::DEFAULT_STYLES['itemExtra'];
        $this->displaysExtra = self::DEFAULT_STYLES['displaysExtra'];
        $this->paddingLeft = self::DEFAULT_STYLES['paddingLeft'];
    }

    public function hasChangedFromDefaults() : bool
    {
        $currentValues = [
            $this->selectedMarker,
            $this->unselectedMarker,
            $this->itemExtra,
            $this->displaysExtra,
            $this->paddingLeft,
        ];

        return $currentValues !== array_values(self::DEFAULT_STYLES);
    }

    public function getMarker(MenuItemInterface $item, bool $selected) : string
    {
        return $selected ? $this->selectedMarker : $this->unselectedMarker;
    }

    public function getSelectedMarker() : string
    {
        return $this->selectedMarker;
    }

    public function setSelectedMarker(string $marker) : self
    {
        $this->selectedMarker = $marker;

        return $this;
    }

    public function getUnselectedMarker() : string
    {
        return $this->unselectedMarker;
    }

    public function setUnselectedMarker(string $marker) : self
    {
        $this->unselectedMarker = $marker;

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

    public function getPaddingLeft() : int
    {
        return $this->paddingLeft;
    }

    public function setPaddingLeft(int $paddingLeft) : self
    {
        $this->paddingLeft = $paddingLeft;

        return $this;
    }
}
