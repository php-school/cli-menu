<?php

namespace MikeyMike\CliMenu\MenuItem;

use MikeyMike\CliMenu\MenuStyle;

/**
 * Class MenuItem
 *
 * @author Michael Woodward <michael@wearejh.com>
 */
class MenuItem implements MenuItemInterface
{
    /**
     * @var string
     */
    private $text;

    /**
     * @param string $text
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

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

    /**
     * Execute the items callable if required
     *
     * @return callable|void
     */
    public function getSelectAction()
    {
        return;
    }

    /**
     * Return the raw string of text
     *
     * @return string
     */
    public function getText()
    {
       return $this->text;
    }
}
