<?php

namespace PhpSchool\CliMenu\MenuItem;

use Assert\Assertion;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Util\StringUtil;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class StaticItem implements MenuItemInterface
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var int
     */
    private $numberOfRows = 0;

    /**
     * @var int
     */
    private $startRowNumber = 0;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * Returns the number of terminal rows the item takes
     */
    public function getNumberOfRows() : int
    {
        return $this->numberOfRows;
    }

    /**
     * Sets the row number the item starts at in the frame
     */
    public function setStartRowNumber(int $rowNumber) : void
    {
        $this->startRowNumber = $rowNumber;
    }

    /**
     * Returns the row number the item starts at in the frame
     */
    public function getStartRowNumber() : int
    {
        return $this->startRowNumber;
    }

    /**
     * The output text for the item
     */
    public function getRows(MenuStyle $style, bool $selected = false) : array
    {
        $rows = explode("\n", StringUtil::wordwrap($this->text, $style->getContentWidth()));

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
        return $this->text;
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
