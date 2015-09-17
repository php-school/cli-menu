<?php

namespace MikeyMike\CliMenu;

use MikeyMike\CliMenu\Exception\InvalidTerminalException;
use \MikeyMike\CliMenu\Terminal\TerminalInterface;
use \MikeyMike\CliMenu\Terminal\UnixTerminal;

/**
 * Class CliMenu
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class CliMenu
{
    /**
     * @var TerminalInterface
     */
    protected $terminal;

    /**
     * @var int
     */
    protected $width = 100;

    /**
     * @var int
     */
    protected $padding = 2;

    /**
     * @var int
     */
    protected $margin = 2;

    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var array
     */
    protected $actions = [];

    /**
     * @var callable
     */
    protected $itemAction;

    /**
     * Initiate the Menu
     *
     * @param array $items
     * @param array $actions
     * @param int   $width
     * @param int   $padding
     * @param int   $margin
     */
    public function __construct(
        array $items,
        callable $itemAction,
        TerminalInterface $terminal = null,
        array $actions = []
    ) {
        $this->items      = $items;
        $this->itemAction = $itemAction;
        $this->terminal   = $terminal ?: new UnixTerminal();
        $this->actions    = array_merge($actions, $this->getDefaultActions());


        $this->configureTerminal();
    }

    /**
     * Configure the terminal to work with CliMenu
     */
    protected function configureTerminal()
    {
        if (!$this->terminal->isTTY()) {
            throw new InvalidTerminalException(
                sprintf('Terminal "%s" is not a valid TTY', $this->terminal->getDetails())
            );
        }

        $this->terminal->setRawMode();
        $this->terminal->enableCursor(false);
        $this->terminal->clear();
    }

    /**
     * Set the style of menu
     *
     * @param int $width
     */
    public function setAppearance($width, $margin, $padding)
    {
        $availableWidth = $this->terminal->getWidth() - ($margin * 2) - ($padding * 2);

        if ($width >= $availableWidth) {
            $width = $availableWidth-1;
        }

        $this->width   = $width;
        $this->padding = $padding;
        $this->margin  = $margin;
    }

    /**
     * Default Menu Actions
     *
     * @return array
     */
    protected function getDefaultActions()
    {
        return [
            'Exit' => function ($name, $terminal) {
                $terminal->killProcess();
            }
        ];
    }

    // TODO: How to handle input, let it be hadled externally ? Handle it here ? How to handle selection, moving etc if external ? Accept input obj if internal ?
}
