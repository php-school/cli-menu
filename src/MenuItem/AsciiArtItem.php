<?php

namespace PhpSchool\CliMenu\MenuItem;

use Assert\Assertion;
use PhpSchool\CliMenu\MenuStyle;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class AsciiArtItem implements MenuItemInterface
{
    /**
     * Possible positions of the ascii art
     */
    const POSITION_CENTER = 'center';
    const POSITION_LEFT   = 'left';
    const POSITION_RIGHT  = 'right';
    
    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $position;

    /**
     * @var int
     */
    private $artLength;

	/**
	 * @var int
	 */
	private $numberOfRows = 0;

	/**
	 * @var int
	 */
	private $startRowNumber = 0;

    public function __construct(string $text, string $position = self::POSITION_CENTER)
    {
        Assertion::inArray($position, [self::POSITION_CENTER, self::POSITION_RIGHT, self::POSITION_LEFT]);
        
        $this->text      = $text;
        $this->position  = $position;
        $this->artLength = max(array_map('mb_strlen', explode("\n", $text)));
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
        $rows = array_map(function ($row) use ($style) {
            $length = mb_strlen($row);

            $padding = $style->getContentWidth() - $length;

            switch ($this->position) {
                case self::POSITION_LEFT:
                    return $row;
                    break;
                case self::POSITION_RIGHT:
                    $row = rtrim($row);
                    $padding = $padding - ($this->artLength - mb_strlen($row));
                    $row = sprintf('%s%s', str_repeat(' ', $padding), $row);
                    break;
                case self::POSITION_CENTER:
                default:
                    $row = rtrim($row);
                    $padding = $padding - ($this->artLength - mb_strlen($row));
                    $left = ceil($padding/2);
                    $right = $padding - $left;
                    $row = sprintf('%s%s%s', str_repeat(' ', $left), $row, str_repeat(' ', $right));
                    break;
            }

            return $row;
		}, explode("\n", $this->text));

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
     * Return the length of the art
     */
    public function getArtLength() : int
    {
        return $this->artLength;
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
