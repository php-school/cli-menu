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
     * @return array
     */
    public function getRows(MenuStyle $style)
    {
        $rows = explode(
            "\n",
            wordwrap(
                sprintf('%s%s', $style->getItemCarat(), $this->text),
                $style->getContentWidth() - strlen($style->getItemCarat())
            )
        );

        return array_map(function ($row, $key) use ($style) {
            if ($key === 0) {
                return $row;
            }

            return sprintf(
                '%s%s',
                str_repeat(' ', strlen($style->getItemCarat())),
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