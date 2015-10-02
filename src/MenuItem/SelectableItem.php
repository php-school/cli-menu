<?php

namespace MikeyMike\CliMenu\MenuItem;

/**
 * Class SelectableItem
 *
 * @author Michael Woodward <michael@wearejh.com>
 */
class SelectableItem implements MenuItemInterface
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var callable
     */
    private $selectAction;

    /**
     * @param string $text
     */
    public function __construct($text, callable $selectAction)
    {
        $this->text         = $text;
        $this->selectAction = $selectAction;
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
        return true;
    }

    /**
     * Execute the items callable if required
     *
     * @return callable|void
     */
    public function getSelectAction()
    {
        return $this->selectAction;
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
