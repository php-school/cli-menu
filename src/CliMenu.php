<?php

namespace PhpSchool\CliMenu;

use PhpSchool\CliMenu\Exception\InvalidInstantiationException;
use PhpSchool\CliMenu\Exception\InvalidTerminalException;
use PhpSchool\CliMenu\Exception\MenuNotOpenException;
use PhpSchool\CliMenu\MenuItem\LineBreakItem;
use PhpSchool\CliMenu\MenuItem\MenuItemInterface;
use PhpSchool\CliMenu\MenuItem\StaticItem;
use PhpSchool\CliMenu\Dialogue\Confirm;
use PhpSchool\CliMenu\Dialogue\Flash;
use PhpSchool\CliMenu\Terminal\TerminalFactory;
use PhpSchool\CliMenu\Terminal\TerminalInterface;
use PhpSchool\CliMenu\Util\StringUtil as s;

/**
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
     * @var ?string
     */
    protected $title;

    /**
     * @var MenuItemInterface[]
     */
    protected $items = [];

    /**
     * @var int
     */
    protected $selectedItem;

    /**
     * @var bool
     */
    protected $open = false;

    /**
     * @var CliMenu|null
     */
    protected $parent;

    /**
     * @var Frame
     */
    private $currentFrame;

    public function __construct(
        ?string $title,
        array $items,
        TerminalInterface $terminal = null,
        MenuStyle $style = null
    ) {
        $this->title      = $title;
        $this->items      = $items;
        $this->terminal   = $terminal ?: TerminalFactory::fromSystem();
        $this->style      = $style ?: new MenuStyle($this->terminal);

        $this->selectFirstItem();
    }

    /**
     * Configure the terminal to work with CliMenu
     *
     * @throws InvalidTerminalException
     */
    protected function configureTerminal() : void
    {
        $this->assertTerminalIsValidTTY();

        $this->terminal->setCanonicalMode();
        $this->terminal->disableCursor();
        $this->terminal->clear();
    }

    /**
     * Revert changes made to the terminal
     *
     * @throws InvalidTerminalException
     */
    protected function tearDownTerminal() : void
    {
        $this->assertTerminalIsValidTTY();

        $this->terminal->setCanonicalMode(false);
        $this->terminal->enableCursor();
    }

    private function assertTerminalIsValidTTY() : void
    {
        if (!$this->terminal->isTTY()) {
            throw new InvalidTerminalException(
                sprintf('Terminal "%s" is not a valid TTY', $this->terminal->getDetails())
            );
        }
    }

    public function setParent(CliMenu $parent) : void
    {
        $this->parent = $parent;
    }

    public function getParent() : ?CliMenu
    {
        return $this->parent;
    }

    public function getTerminal() : TerminalInterface
    {
        return $this->terminal;
    }

    public function isOpen() : bool
    {
        return $this->open;
    }

    /**
     * Add a new Item to the menu
     */
    public function addItem(MenuItemInterface $item) : void
    {
        $this->items[] = $item;
        
        if (count($this->items) === 1) {
            $this->selectFirstItem();
        }
    }

    /**
     * Set the selected pointer to the first selectable item
     */
    private function selectFirstItem() : void
    {
        foreach ($this->items as $key => $item) {
            if ($item->canSelect()) {
                $this->selectedItem = $key;
                break;
            }
        }
    }

    /**
     * Display menu and capture input
     */
    private function display() : void
    {
        $this->draw();

        while ($this->isOpen() && $input = $this->terminal->getKeyedInput()) {
            switch ($input) {
                case 'up':
                case 'down':
                    $previousItem = $this->getSelectedItem();
                    $this->moveSelection($input);
                    $newItem = $this->getSelectedItem();
                    if ($previousItem !== $newItem) {
                        $this->draw($previousItem, $newItem);
                    }
                    break;
                case 'enter':
                    $this->executeCurrentItem();
                    break;
            }
        }
    }

    /**
     * Move the selection in a given direction, up / down
     */
    protected function moveSelection(string $direction) : void
    {
        do {
            $itemKeys = array_keys($this->items);

            $direction === 'up'
                ? $this->selectedItem--
                : $this->selectedItem++;

            if (!array_key_exists($this->selectedItem, $this->items)) {
                $this->selectedItem  = $direction === 'up'
                    ? end($itemKeys)
                    : reset($itemKeys);
            } elseif ($this->getSelectedItem()->canSelect()) {
                return;
            }
        } while (!$this->getSelectedItem()->canSelect());
    }

    public function getSelectedItem() : MenuItemInterface
    {
        return $this->items[$this->selectedItem];
    }

    /**
     * Execute the current item
     */
    protected function executeCurrentItem() : void
    {
        $item = $this->getSelectedItem();

        if ($item->canSelect()) {
            $callable = $item->getSelectAction();
            $callable($this);
        }
    }

    /**
     * Redraw the menu
     */
    public function redraw() : void
    {
        if (!$this->isOpen()) {
            throw new MenuNotOpenException;
        }

        $this->draw();
    }

    /**
     * Draw the menu to STDOUT
     */
    protected function draw(MenuItemInterface $previousItem = null, MenuItemInterface $newItem = null) : void
    {

        $frame = new Frame;

        $frame->newLine(2);

        if ($this->title) {
            $frame->addRows($this->drawMenuItem(new LineBreakItem()));
            $frame->addRows($this->drawMenuItem(new StaticItem($this->title)));
            $frame->addRows($this->drawMenuItem(new LineBreakItem($this->style->getTitleSeparator())));
        }

        array_map(function ($item, $index) use ($frame) {
            $frame->addRows($this->drawMenuItem($item, $index === $this->selectedItem));
        }, $this->items, array_keys($this->items));

        $frame->addRows($this->drawMenuItem(new LineBreakItem()));

        $frame->newLine(2);

        if ($previousItem && $newItem) {

            $rows = $frame->getRows();

            $this->terminal->moveCursorToTop();
            $this->terminal->moveCursorDown($previousItem->getStartRowNumber());
            echo rtrim($rows[$previousItem->getStartRowNumber()], "\n\r");

            $this->terminal->moveCursorToTop();
            $this->terminal->moveCursorDown($newItem->getStartRowNumber());
            echo rtrim($rows[$newItem->getStartRowNumber()], "\n\r");

        } else {

            $this->terminal->clean();
            $this->terminal->moveCursorToTop();

            foreach ($frame->getRows() as $row) {
                echo $row;
            }

        }

        $this->currentFrame = $frame;
    }

    /**
     * Draw a menu item
     */
    protected function drawMenuItem(MenuItemInterface $item, bool $selected = false) : array
    {
        $rows = $item->getRows($this->style, $selected);

        $setColour = $selected
            ? $this->style->getSelectedSetCode()
            : $this->style->getUnselectedSetCode();

        $unsetColour = $selected
            ? $this->style->getSelectedUnsetCode()
            : $this->style->getUnselectedUnsetCode();

        $rows = array_map(function ($row) use ($setColour, $unsetColour) {
            return sprintf(
                "%s%s%s%s%s%s%s\n\r",
                str_repeat(' ', $this->style->getMargin()),
                $setColour,
                str_repeat(' ', $this->style->getPadding()),
                $row,
                str_repeat(' ', $this->style->getRightHandPadding(mb_strlen(s::stripAnsiEscapeSequence($row)))),
                $unsetColour,
                str_repeat(' ', $this->style->getMargin())
            );
        }, $rows);

        $item->setNumberOfRows( count( $rows ) );

        return array( $rows, $item );
    }

    /**
     * @throws InvalidTerminalException
     */
    public function open() : void
    {
        if ($this->isOpen()) {
            return;
        }

        $this->configureTerminal();
        $this->open = true;
        $this->display();
    }

    /**
     * Close the menu
     *
     * @throws InvalidTerminalException
     */
    public function close() : void
    {
        $menu = $this;

        do {
            $menu->closeThis();
            $menu = $menu->getParent();
        } while (null !== $menu);
        
        $this->tearDownTerminal();
    }

    public function closeThis() : void
    {
        $this->terminal->clean();
        $this->terminal->moveCursorToTop();
        $this->open = false;
    }

    /**
     * @return MenuItemInterface[]
     */
    public function getItems() : array
    {
        return $this->items;
    }

    public function removeItem(MenuItemInterface $item) : void
    {
        $key = array_search($item, $this->items, true);

        if (false === $key) {
            throw new \InvalidArgumentException('Item does not exist in menu');
        }

        unset($this->items[$key]);
        $this->items = array_values($this->items);
    }

    public function getStyle() : MenuStyle
    {
        return $this->style;
    }

    public function getCurrentFrame() : Frame
    {
        return $this->currentFrame;
    }

    public function flash(string $text) : Flash
    {
        if (strpos($text, "\n") !== false) {
            throw new \InvalidArgumentException;
        }

        $style = (new MenuStyle($this->terminal))
            ->setBg('yellow')
            ->setFg('red');

        return new Flash($this, $style, $this->terminal, $text);
    }

    public function confirm($text) : Confirm
    {
        if (strpos($text, "\n") !== false) {
            throw new \InvalidArgumentException;
        }

        $style = (new MenuStyle($this->terminal))
            ->setBg('yellow')
            ->setFg('red');

        return new Confirm($this, $style, $this->terminal, $text);
    }
}
