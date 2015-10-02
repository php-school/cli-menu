<?php

namespace MikeyMike\CliMenu;

use MikeyMike\CliMenu\Exception\InvalidTerminalException;
use MikeyMike\CliMenu\MenuItem\LineBreakItem;
use MikeyMike\CliMenu\MenuItem\MenuItem;
use MikeyMike\CliMenu\MenuItem\MenuItemInterface;
use MikeyMike\CliMenu\MenuItem\SelectableItem;
use MikeyMike\CliMenu\MenuItem\StaticItem;
use MikeyMike\CliMenu\Terminal\TerminalFactory;
use \MikeyMike\CliMenu\Terminal\TerminalInterface;

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
     * @var MenuStyle
     */
    protected $style;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var array
     */
    protected $actions = [];

    /**
     * @var array
     */
    protected $allItems = [];

    /**
     * @var callable
     */
    protected $itemAction;

    /**
     * @var int
     */
    protected $selectedItem;

    /**
     * Initiate the Menu
     *
     * @param $title
     * @param array $items
     * @param callable $itemAction
     * @param TerminalInterface $terminal
     * @param MenuStyle $style
     * @param array $actions
     * @throws InvalidTerminalException
     */
    public function __construct(
        $title,
        array $items,
        callable $itemAction,
        array $actions = [],
        TerminalInterface $terminal = null,
        MenuStyle $style = null
    ) {
        $this->title      = $title;
        $this->itemAction = $itemAction;
        $this->terminal   = $terminal ?: TerminalFactory::fromSystem();
        $this->style      = $style ?: new MenuStyle($this->terminal);
        $this->items      = $items ?: [new StaticItem('An empty menu is never fun...')];
        $this->actions    = array_merge(
            [new LineBreakItem('-')],
            $actions,
            $this->getDefaultActions()
        );
        $this->allItems   = array_merge($this->items, $this->actions);

        foreach ($this->allItems as $key => $item) {
            if ($item->canSelect()) {
                $this->selectedItem = $key;
                break;
            }
        }

        $this->configureTerminal();
    }

    /**
     * Configure the terminal to work with CliMenu
     *
     * @throws InvalidTerminalException
     */
    protected function configureTerminal()
    {
        if (!$this->terminal->isTTY()) {
            throw new InvalidTerminalException(
                sprintf('Terminal "%s" is not a valid TTY', $this->terminal->getDetails())
            );
        }

        $this->terminal->setRawMode();
        $this->terminal->toggleCursor();
        $this->terminal->clear();
    }

    /**
     * Revert changes made to the terminal
     *
     * @throws InvalidTerminalException
     */
    protected function tearDownTerminal()
    {
        if (!$this->terminal->isTTY()) {
            throw new InvalidTerminalException(
                sprintf('Terminal "%s" is not a valid TTY', $this->terminal->getDetails())
            );
        }

        $this->terminal->setRawMode(false);
        $this->terminal->toggleCursor();
    }

    /**
     * Default Menu Actions
     *
     * @return array
     */
    protected function getDefaultActions()
    {
        return [
            new SelectableItem('Exit', function (CliMenu $menu) {
                $menu->close();
            })
        ];
    }

    /**
     * Display menu and capture input
     */
    public function display()
    {
        $this->draw();

        while ($input = $this->terminal->getKeyedInput()) {
            switch ($input) {
                case 'up':
                case 'down':
                    $this->moveSelection($input);
                    break;
                case 'enter':
                    $this->executeCurrentItem();
                    break;
            }

            $this->draw();
        }
    }

    /**
     * Move the selection ina  given direction, up / down
     *
     * @param $direction
     */
    protected function moveSelection($direction)
    {
        do {
            $itemKeys = array_keys($this->allItems);

            $direction === 'up'
                ? $this->selectedItem--
                : $this->selectedItem++;

            if (!array_key_exists($this->selectedItem, $this->allItems)) {
                $this->selectedItem  = $direction === 'up'
                    ? end($itemKeys)
                    : reset($itemKeys);
            } elseif ($this->getSelectedItem()->canSelect()) {
                return;
            }

        } while (!$this->getSelectedItem()->canSelect());
    }

    /**
     * @return MenuItemInterface
     */
    public function getSelectedItem()
    {
        return $this->allItems[$this->selectedItem];
    }

    /**
     * Execute the current item
     */
    protected function executeCurrentItem()
    {
        $action = $this->getSelectedItem() instanceof MenuItem
            ? $this->itemAction
            : $this->getSelectedItem()->getSelectAction();

        if (is_callable($action)) {
            $action($this);
        }
    }

    /**
     * Draw the menu to stdout
     */
    protected function draw()
    {
        $this->terminal->moveCursorToTop();

        echo "\n\n";

        $this->drawMenuItem(new LineBreakItem());
        $this->drawMenuItem(new StaticItem($this->title));
        $this->drawMenuItem(new LineBreakItem('='));

        array_map(function ($item, $index) {
            $this->drawMenuItem($item, $index === $this->selectedItem);
        }, $this->allItems, array_keys($this->allItems));

        $this->drawMenuItem(new LineBreakItem());

        echo "\n\n";
    }

    /**
     * Draw a menu item
     *
     * @param MenuItemInterface $item
     * @param bool|false $selected
     */
    protected function drawMenuItem(MenuItemInterface $item, $selected = false)
    {
        $rows = $item->getRows($this->style->getContentWidth());

        $setColour = $selected
            ? $this->style->getSelectedSetCode()
            : $this->style->getUnselectedSetCode();

        $unsetColour = $selected
            ? $this->style->getSelectedUnsetCode()
            : $this->style->getUnselectedUnsetCode();

        foreach ($rows as $row) {
            echo sprintf(
                "%s%s%s%s%s%s%s",
                str_repeat(' ', $this->style->getMargin()),
                $setColour,
                str_repeat(' ', $this->style->getPadding()),
                $row,
                str_repeat(' ', $this->style->getRightHandPadding(mb_strlen($row))),
                $unsetColour,
                str_repeat(' ', $this->style->getMargin())
            );

            echo "\n\r";
        }
    }

    /**
     * @return MenuStyle
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Close the menu
     *
     * @throws InvalidTerminalException
     */
    public function close()
    {
        $this->tearDownTerminal();
        exit();
    }
}
