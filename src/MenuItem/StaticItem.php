<?php

namespace MikeyMike\CliMenu\MenuItem;

/**
 * Class StaticItem
 *
 * @author Michael Woodward <michael@wearejh.com>
 */
class StaticItem implements MenuItemInterface
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
     * @param int $menuWidth
     * @return array
     */
    public function getRows($menuWidth)
    {
        return explode("\n", wordwrap($this->text, $menuWidth));
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
}
