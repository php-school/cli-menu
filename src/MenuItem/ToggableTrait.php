<?php

namespace PhpSchool\CliMenu\MenuItem;

use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Util\StringUtil;

trait ToggableTrait
{
    /**
     * @var bool
     */
    private $checked = false;

    /**
     * The output text for the item
     *
     * @param MenuStyle $style
     * @param bool $selected Currently unused in this class
     * @return array
     */
    public function getRows(MenuStyle $style, bool $selected = false) : array
    {
        $markerTypes = [
            true => $this instanceof CheckableItem
                ? $style->getCheckedMarker()
                : $style->getRadioMarker(),
            false => $this instanceof CheckableItem
                ? $style->getUncheckedMarker()
                : $style->getUnradioMarker(),
        ];

        $marker = sprintf("%s", $markerTypes[$this->checked]);

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

    public function getChecked() : bool
    {
        return $this->checked;
    }
}
