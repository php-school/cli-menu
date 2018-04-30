<?php

namespace PhpSchool\CliMenu\MenuItem;

use Assert\Assertion;
use PhpSchool\CliMenu\MenuStyle;

/**
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
     * @var int
     */
    private $numberOfRows = 0;

    /**
     * @var int
     */
    private $startRowNumber = 0;

    public function __construct(string $breakChar = ' ', int $lines = 1)
    {
        $this->breakChar = $breakChar;
        $this->lines     = $lines;
    }

    /**
     * Returns the number of terminal rows the item takes
     */
    public function getNumberOfRows() {
        return $this->numberOfRows;
    }

    /**
     * Sets the row number the item starts at in the frame
     */
    public function setStartRowNumber(int $rowNumber) {
        $this->startRowNumber = $rowNumber;
    }

    /**
     * Returns the row number the item starts at in the frame
     */
    public function getStartRowNumber() {
        return $this->startRowNumber;
    }

    /**
     * The output text for the item
     */
    public function getRows(MenuStyle $style, bool $selected = false) : array
    {
        $rows = explode(
            "\n",
            rtrim(str_repeat(sprintf(
                "%s\n",
                mb_substr(str_repeat($this->breakChar, $style->getContentWidth()), 0, $style->getContentWidth())
            ), $this->lines))
        );

        $this->numberOfRows = count($rows);

        return $rows;
    }

    /**
     * Can the item be selected
     */
    public function canSelect() : bool
    {
        return false;
    }

    /**
     * Execute the items callable if required
     */
    public function getSelectAction() : ?callable
    {
        return null;
    }

    /**
     * Return the raw string of text
     */
    public function getText() : string
    {
        return $this->breakChar;
    }

    /**
     * Whether or not the menu item is showing the menustyle extra value
     */
    public function showsItemExtra() : bool
    {
        return false;
    }

    /**
     * Enable showing item extra
     */
    public function showItemExtra() : void
    {
        //noop
    }

    /**
     * Disable showing item extra
     */
    public function hideItemExtra() : void
    {
        //noop
    }
}
