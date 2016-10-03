<?php

namespace PhpSchool\CliMenu\MenuItem;

use Assert\Assertion;
use PhpSchool\CliMenu\CliMenu;

/**
 * Class MenuMenuItem
 *
 * @package PhpSchool\CliMenu\MenuItem
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class MenuMenuItem implements MenuItemInterface
{
    use SelectableTrait;
    
    /**
     * @var CliMenu
     */
    private $subMenu;

    /**
     * @param string $text
     * @param CliMenu $subMenu
     * @param bool $disabled
     */
    public function __construct($text, CliMenu $subMenu, $disabled = false)
    {
        Assertion::string($text);
        
        $this->text     = $text;
        $this->subMenu  = $subMenu;
        $this->disabled = $disabled;
    }

    /**
     * Execute the items callable if required
     *
     * @return callable
     */
    public function getSelectAction()
    {
        return [$this, 'showSubMenu'];
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
     * Display the sub menu
     * @param CliMenu $parentMenu
     */
    public function showSubMenu(CliMenu $parentMenu)
    {
        $parentMenu->closeThis();
        $this->subMenu->open();
    }
}
