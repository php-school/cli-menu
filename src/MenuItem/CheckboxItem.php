<?php

namespace PhpSchool\CliMenu\MenuItem;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Util\StringUtil;
use PhpSchool\CliMenu\Style\CheckboxStyle;

class CheckboxItem implements MenuItemInterface, ToggableItemInterface
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var callable
     */
    private $selectAction;

    /**
     * @var bool
     */
    private $showItemExtra;

    /**
     * @var bool
     */
    private $disabled;

    /**
     * The current checkbox state
     *
     * @var bool
     */
    private $checked = false;

    /**
     * @var CheckboxStyle
     */
    private $style;

    public function __construct(
        string $text,
        callable $selectAction,
        bool $showItemExtra = false,
        bool $disabled = false
    ) {
        $this->text = $text;
        $this->selectAction = $selectAction;
        $this->showItemExtra = $showItemExtra;
        $this->disabled = $disabled;

        $this->style = new CheckboxStyle();
    }

    /**
     * The output text for the item
     */
    public function getRows(MenuStyle $style, bool $selected = false) : array
    {
        $marker = sprintf("%s", $this->style->getMarker($this->checked));

        $itemExtra = $this->style->getItemExtra();

        $length = $this->style->getDisplaysExtra()
            ? $style->getContentWidth() - (mb_strlen($itemExtra) + 2)
            : $style->getContentWidth();

        $rows = explode(
            "\n",
            StringUtil::wordwrap(
                sprintf('%s%s', $marker, $this->text),
                $length,
                sprintf("\n%s", str_repeat(' ', mb_strlen($marker)))
            )
        );

        return array_map(function ($row, $key) use ($style, $length, $itemExtra) {
            $text = $this->disabled ? $style->getDisabledItemText($row) : $row;

            if ($key === 0) {
                return $this->showItemExtra
                    ? sprintf('%s%s  %s', $text, str_repeat(' ', $length - mb_strlen($row)), $itemExtra)
                    : $text;
            }

            return $text;
        }, $rows, array_keys($rows));
    }

    /**
     * Return the raw string of text
     */
    public function getText() : string
    {
        return $this->text;
    }

    /**
     * Set the raw string of text
     */
    public function setText(string $text) : void
    {
        $this->text = $text;
    }

    /**
     * Execute the items callable if required
     */
    public function getSelectAction() : ?callable
    {
        return function (CliMenu $cliMenu) {
            $this->toggle();
            $cliMenu->redraw();

            return ($this->selectAction)($cliMenu);
        };
    }

    /**
     * Can the item be selected
     */
    public function canSelect() : bool
    {
        return !$this->disabled;
    }

    /**
     * Whether or not we are showing item extra
     */
    public function showsItemExtra() : bool
    {
        return $this->showItemExtra;
    }

    /**
     * Enable showing item extra
     */
    public function showItemExtra() : void
    {
        $this->showItemExtra = true;
    }

    /**
     * Disable showing item extra
     */
    public function hideItemExtra() : void
    {
        $this->showItemExtra = false;
    }

    /**
     * Whether or not the item is checked
     */
    public function getChecked() : bool
    {
        return $this->checked;
    }

    /**
     * Sets checked state to true
     */
    public function setChecked() : void
    {
        $this->checked = true;
    }

    /**
     * Sets checked state to false
     */
    public function setUnchecked() : void
    {
        $this->checked = false;
    }

    /**
     * Toggles checked state
     */
    public function toggle() : void
    {
        $this->checked = !$this->checked;
    }

    public function getStyle() : CheckboxStyle
    {
        return $this->style;
    }

    public function setStyle(CheckboxStyle $style) : self
    {
        $this->style = $style;

        return $this;
    }
}
