<?php

namespace PhpSchool\CliMenu\MenuItem;

use Assert\Assertion;

/**
 * Class SelectableItem
 *
 * @package PhpSchool\CliMenu\MenuItem
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class SelectableItem implements MenuItemInterface
{
    use SelectableTrait;

    /**
     * @var callable
     */
    private $selectAction;

    /**
     * @param string $text
     * @param callable $selectAction
     * @param bool $showItemExtra
     * @param bool $disabled
     */
    public function __construct($text, callable $selectAction, $data = [], $showItemExtra = false, $disabled = false)
    {
        Assertion::string($text);
     
        $this->text          = $text;
        $this->data          = $data;
        $this->selectAction  = $selectAction;
        $this->showItemExtra = (bool) $showItemExtra;
        $this->disabled      = $disabled;
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
    
    /**
     * Return the raw data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
