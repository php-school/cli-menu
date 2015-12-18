<?php

namespace PhpSchool\CliMenu\MenuItem;

use Assert\Assertion;
use PhpSchool\CliMenu\MenuStyle;

/**
 * Class AsciiArtItem
 *
 * @package PhpSchool\CliMenu\MenuItem
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
     * @param string $text
     * @param string $position
     */
    public function __construct($text, $position = self::POSITION_CENTER)
    {
        Assertion::string($text);
        Assertion::inArray($position, [self::POSITION_CENTER, self::POSITION_RIGHT, self::POSITION_LEFT]);
        
        $this->text      = $text;
        $this->position  = $position;
        $this->artLength = max(array_map('mb_strlen', explode("\n", $text)));
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
        $justificationHelper = new Helper\AsciiArtItemJustificationHelper(
            $this->position,
            $style->getContentWidth(),
            $this->artLength);

        return array_map(function ($row) use ($justificationHelper) {
            return $justificationHelper->justifyRow($row);
        }, explode("\n", $this->text));
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
     * @return void
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
