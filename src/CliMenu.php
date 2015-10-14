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
     * @param $title
     * @param array $items
     * @param callable $itemAction
     * @param array $actions
     * @param TerminalInterface|null $terminal
     * @param MenuStyle|null $style
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
        $this->items      = $items;
        $this->terminal   = $terminal ?: TerminalFactory::fromSystem();
        $this->style      = $style ?: new MenuStyle();

        $this->actions    = array_merge(
            $actions,
            [new LineBreakItem('-')],
            $this->getDefaultActions()
        );

        $this->buildAllItems();
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

        $this->terminal->setCanonicalMode();
        $this->terminal->disableCursor();
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

        $this->terminal->setCanonicalMode(false);
        $this->terminal->enableCursor();
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
     * Update style confuration based on menu
     */
    public function updateStyle()
    {
        $this->style->setDisplaysExtra(!empty(array_filter($this->items, function (MenuItemInterface $item) {
            return $item->showsItemExtra();
        })));
    }

    /**
     * Add a new Item to the listing
     *
     * @param MenuItemInterface $item
     */
    public function addItem(MenuItemInterface $item)
    {
        $this->items[] = $item;
        $this->buildAllItems();
        $this->updateStyle();
    }

    /**
     * Add a new Action before the default actions
     *
     * @param MenuItemInterface $action
     */
    public function addAction(MenuItemInterface $action)
    {
        array_splice($this->actions, -1, 0, [$action]);
        $this->buildAllItems();
    }

    /**
     * Build allItems array from items and actions
     */
    private function buildAllItems()
    {
        $this->allItems = array_merge($this->items, $this->actions);
        $this->selectFirstItem();
    }

    /**
     * Set the selected pointer to the first selectable item
     */
    private function selectFirstItem()
    {
        foreach ($this->allItems as $key => $item) {
            if ($item->canSelect()) {
                $this->selectedItem = $key;
                break;
            }
        }
    }

    /**
     * Add item action callback
     *
     * @param callable $callback
     */
    public function setItemCallback(callable $callback)
    {
        $this->itemAction = $callback;
    }

    /**
     * Set the terminal to use
     *
     * @param TerminalInterface $terminal
     */
    public function setTerminal(TerminalInterface $terminal)
    {
        $this->terminal = $terminal;
    }

    /**
     * Set the menu style
     *
     * @param MenuStyle $style
     */
    public function setMenuStyle(MenuStyle $style)
    {
        $this->style = $style;
    }

    /**
     * Get the menus style
     *
     * @return MenuStyle
     */
    public function getMenuStyle()
    {
        return $this->style;
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
                    $this->draw();
                    break;
                case 'enter':
                    $this->executeCurrentItem();
                    break;
            }
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
        $this->terminal->clean();
        $this->terminal->moveCursorToTop();

        echo "\n\n";

        if (is_string($this->title)) {
            $this->drawMenuItem(new LineBreakItem());
            $this->drawMenuItem(new StaticItem($this->title));
            $this->drawMenuItem(new LineBreakItem('='));
        }

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
        $rows = $item->getRows($this->style, $selected);

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
