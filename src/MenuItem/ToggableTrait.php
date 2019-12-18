<?php

namespace PhpSchool\CliMenu\MenuItem;

use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Style\ItemStyleInterface;
use PhpSchool\CliMenu\Util\StringUtil;

trait ToggableTrait
{
    /**
     * @var callable
     */
    private $selectAction;

    private $text = '';

    private $showItemExtra = false;

    private $disabled = false;

    private $checked = false;

    /**
     * @var ItemStyleInterface;
     */
    private $style;

    /**
     * The output text for the item
     */
    public function getRows(MenuStyle $style, bool $selected = false) : array
    {
        $marker = sprintf("%s", $this->style->getMarker($this->checked));

        $length = $style->getDisplaysExtra()
            ? $style->getContentWidth() - (mb_strlen($style->getItemExtra()) + 2)
            : $style->getContentWidth();

        $rows = explode(
            "\n",
            StringUtil::wordwrap(
                sprintf('%s%s', $marker, $this->text),
                $length,
                sprintf("\n%s", str_repeat(' ', mb_strlen($marker)))
            )
        );

        return array_map(function ($row, $key) use ($style, $length) {
            $text = $this->disabled ? $style->getDisabledItemText($row) : $row;

            if ($key === 0) {
                return $this->showItemExtra
                    ? sprintf('%s%s  %s', $text, str_repeat(' ', $length - mb_strlen($row)), $style->getItemExtra())
                    : $text;
            }

            return $text;
        }, $rows, array_keys($rows));
    }

    /**
     * Toggles checked state
     */
    public function toggle() : void
    {
        $this->checked = !$this->checked;
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
     * Whether or not the item is checked
     */
    public function getChecked() : bool
    {
        return $this->checked;
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
     * Can the item be selected
     */
    public function canSelect() : bool
    {
        return !$this->disabled;
    }

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
}
