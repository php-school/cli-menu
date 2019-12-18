<?php

namespace PhpSchool\CliMenu\MenuItem;

use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Style;
use PhpSchool\CliMenu\Util\StringUtil;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
trait SelectableTrait
{
    private $text = '';

    private $showItemExtra = false;

    private $disabled = false;

    /**
     * @var Style\ItemStyleInterface;
     */
    private $style;

    public function getStyle() : Style\ItemStyleInterface
    {
        if (!$this->style) {
            $this->style = new Style\SelectableStyle();
        }

        return $this->style;
    }

    /**
     * @param Style\ItemStyleInterface|Style\SelectableStyle $style
     * @return $this
     */
    public function setStyle(Style\ItemStyleInterface $style) : self
    {
        $this->style = $style;

        return $this;
    }

    /**
     * The output text for the item
     */
    public function getRows(MenuStyle $style, bool $selected = false) : array
    {
        $marker = sprintf("%s", $this->style->getMarker($selected));

        $length = $this->style->getDisplaysExtra()
            ? $style->getContentWidth() - (mb_strlen($this->style->getItemExtra()) + 2)
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
                    ? sprintf('%s%s  %s', $text, str_repeat(' ', $length - mb_strlen($row)), $this->style->getItemExtra())
                    : $text;
            }

            return $text;
        }, $rows, array_keys($rows));
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
