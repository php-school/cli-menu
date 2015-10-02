<?php

namespace MikeyMike\CliMenu\MenuItem;

/**
 * Class MenuItem
 *
 * @author Michael Woodward <michael@wearejh.com>
 */
class MenuItem implements MenuItemInterface
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
        return explode(
            "\n",
            wordwrap(
                sprintf('>> %s', $this->text),
                $menuWidth - 3
            )
        );
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
