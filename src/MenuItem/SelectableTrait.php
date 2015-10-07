<?php

namespace MikeyMike\CliMenu\MenuItem;

use MikeyMike\CliMenu\MenuStyle;

/**
 * Class SelectableTrait
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
trait SelectableTrait
{
    /**
     * @var string
     */
    private $text;

    /**
     * The output text for the item
     *
     * @param MenuStyle $style
     * @param bool $selected
     * @return array
     */
    public function getRows(MenuStyle $style, $selected = false)
    {
        $marker = sprintf("%s%s", $style->getMarker($selected), ' ');

        $rows = explode(
            "\n",
            wordwrap(
                sprintf('%s%s', $marker, $this->text),
                $style->getContentWidth()
            )
        );

        return array_map(function ($row, $key) use ($style, $marker) {
            if ($key === 0) {
                return $row;
            }

            return sprintf(
                '%s%s',
                str_repeat(' ', mb_strlen($marker)),
                $row
            );
        }, $rows, array_keys($rows));
    }

    /**
     * Can the item be selected
     *
     * @return bool
     */
    public function canSelect()
    {
        return true;
    }
}