<?php

namespace PhpSchool\CliMenu\MenuItem;

use Assert\Assertion;
use PhpSchool\CliMenu\MenuStyle;

/**
 * Class LineBreakItem
 *
 * @package PhpSchool\CliMenu\MenuItem
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class LineBreakItem implements MenuItemInterface
{
    /**
     * @var string
     */
    private $breakChar;

    /**
     * @var int
     */
    private $lines;

    /**
     * Initialise text item
     *
     * @param string $breakChar
     * @param int    $lines
     */
    public function __construct($breakChar = ' ', $lines = 1)
    {
        Assertion::string($breakChar);
        Assertion::integer($lines);
        
        $this->breakChar = $breakChar;
        $this->lines     = $lines;
    }

    /**
     * The output text for the item
     *
     * @param MenuStyle $style
     * @param bool $selected
     * @return array
     */
    public function getRows(MenuStyle $style, $selected = false)
    {
        return explode(
            "\n",
            rtrim(str_repeat(sprintf(
                "%s\n",
                mb_substr(str_repeat($this->breakChar, $style->getContentWidth()), 0, $style->getContentWidth())
            ), $this->lines))
        );
    }

    /**
     * Can the item be selected
     *
     * @return bool
     */
    public function canSelect()
    {
        return false;
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
        return $this->breakChar;
    }

    /**
     * Whether or not the menu item is showing the menustyle extra value
     *
     * @return bool
     */
    public function showsItemExtra()
    {
        return false;
    }
}
