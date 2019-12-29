<?php

namespace PhpSchool\CliMenu\Style;

class CheckboxStyle implements Selectable
{
    private const DEFAULT_STYLES = [
        'checkedMarker' => '[✔] ',
        'uncheckedMarker' => '[ ] ',
        'itemExtra' => '✔',
        'displaysExtra' => false,
    ];

    /**
     * @var string
     */
    private $checkedMarker;

    /**
     * @var string
     */
    private $uncheckedMarker;

    /**
     * @var string
     */
    private $itemExtra;

    /**
     * @var bool
     */
    private $displaysExtra;

    public function __construct()
    {
        $this->checkedMarker = self::DEFAULT_STYLES['checkedMarker'];
        $this->uncheckedMarker = self::DEFAULT_STYLES['uncheckedMarker'];
        $this->itemExtra = self::DEFAULT_STYLES['itemExtra'];
        $this->displaysExtra = self::DEFAULT_STYLES['displaysExtra'];
    }

    public function hasChangedFromDefaults() : bool
    {
        $currentValues = [
            $this->checkedMarker,
            $this->uncheckedMarker,
            $this->itemExtra,
            $this->displaysExtra,
        ];

        return $currentValues !== array_values(self::DEFAULT_STYLES);
    }

    public function getMarker(bool $selected) : string
    {
        return $selected ? $this->checkedMarker : $this->uncheckedMarker;
    }

    public function getCheckedMarker() : string
    {
        return $this->checkedMarker;
    }

    public function setCheckedMarker(string $marker) : self
    {
        $this->checkedMarker = $marker;

        return $this;
    }

    public function getUncheckedMarker() : string
    {
        return $this->uncheckedMarker;
    }

    public function setUncheckedMarker(string $marker) : self
    {
        $this->uncheckedMarker = $marker;

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
