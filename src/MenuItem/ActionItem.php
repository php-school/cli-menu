<?php

namespace MikeyMike\CliMenu\MenuItem;

use MikeyMike\CliMenu\CliMenu;
use MikeyMike\CliMenu\MenuStyle;

/**
 * Class ActionItem
 * @author Michael Woodward <michael@wearejh.com>
 */
class ActionItem implements MenuItemInterface
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var callable
     */
    private $action;

    /**
     * Initialise action item
     *
     * @param string $text
     * @param callable $action
     */
    public function __construct($text, callable $action)
    {
        $this->text   = $text;
        $this->action = $action;
    }

    /**
     * The output text for the item
     *
     * @param MenuStyle $style
     * @return string
     */
    public function getText(MenuStyle $style)
    {
        return $this->text;
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
     * Return the action callable
     */
    public function execute(CliMenu $menu)
    {
        call_user_func($this->action, $menu);
    }
}
