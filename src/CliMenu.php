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
 * Class CliMenu
 *
 * @package PhpSchool\CliMenu
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
     * @var Frame|null
     */
    private $currentFrame;

    /**
     * @param string $title
     * @param array $items
     * @param TerminalInterface|null $terminal
     * @param MenuStyle|null $style
     *
     * @throws InvalidTerminalException
     */
    public function __construct(
        $title,
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
    protected function configureTerminal()
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
    protected function tearDownTerminal()
    {
        $this->assertTerminalIsValidTTY();

        $this->terminal->setCanonicalMode(false);
        $this->terminal->enableCursor();
    }

    private function assertTerminalIsValidTTY()
    {
        if (!$this->terminal->isTTY()) {
            throw new InvalidTerminalException(
                sprintf('Terminal "%s" is not a valid TTY', $this->terminal->getDetails())
            );
        }
    }

    /**
     * @param CliMenu $parent
     */
    public function setParent(CliMenu $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return CliMenu|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return TerminalInterface
     */
    public function getTerminal()
    {
        return $this->terminal;
    }

    /**
     * @return bool
     */
    public function isOpen()
    {
        return $this->open;
    }

    /**
     * Add a new Item to the listing
     *
     * @param MenuItemInterface $item
     */
    public function addItem(MenuItemInterface $item)
    {
        $this->items[] = $item;
        
        if (count($this->items) === 1) {
            $this->selectFirstItem();
        }
    }

    /**
     * Set the selected pointer to the first selectable item
     */
    private function selectFirstItem()
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
    private function display()
    {
        $this->draw();

        while ($this->isOpen() && $input = $this->terminal->getKeyedInput()) {
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
     * Move the selection in a given direction, up / down
     *
     * @param $direction
     */
    protected function moveSelection($direction)
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

    /**
     * @return MenuItemInterface
     */
    public function getSelectedItem()
    {
        return $this->items[$this->selectedItem];
    }

    /**
     * Execute the current item
     */
    protected function executeCurrentItem()
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
    public function redraw()
    {
        if (!$this->isOpen()) {
            throw new MenuNotOpenException;
        }

        $this->draw();
    }

    /**
     * Draw the menu to STDOUT
     */
    protected function draw()
    {
        $this->terminal->clean();
        $this->terminal->moveCursorToTop();

        $frame = new Frame;

        $frame->newLine(2);

        if (is_string($this->title)) {
            $frame->addRows($this->drawMenuItem(new LineBreakItem()));
            $frame->addRows($this->drawMenuItem(new StaticItem($this->title)));
            $frame->addRows($this->drawMenuItem(new LineBreakItem($this->style->getTitleSeparator())));
        }

        array_map(function ($item, $index) use ($frame) {
            $frame->addRows($this->drawMenuItem($item, $index === $this->selectedItem));
        }, $this->items, array_keys($this->items));

        $frame->addRows($this->drawMenuItem(new LineBreakItem()));

        $frame->newLine(2);

        foreach ($frame->getRows() as $row) {
            echo $row;
        }

        $this->currentFrame = $frame;
    }

    /**
     * Draw a menu item
     *
     * @param MenuItemInterface $item
     * @param bool|false $selected
     * @return array
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

        return array_map(function ($row) use ($setColour, $unsetColour) {
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
    }

    /**
     * @throws InvalidTerminalException
     */
    public function open()
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
    public function close()
    {
        $menu = $this;

        do {
            $menu->closeThis();
            $menu = $menu->getParent();
        } while (null !== $menu);
        
        $this->tearDownTerminal();
    }

    /**
     * @throws InvalidTerminalException
     */
    public function closeThis()
    {
        $this->terminal->clean();
        $this->terminal->moveCursorToTop();
        $this->open = false;
    }

    /**
     * @return MenuItemInterface[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param MenuItemInterface $item
     */
    public function removeItem(MenuItemInterface $item)
    {
        $key = array_search($item, $this->items);

        if (false === $key) {
            throw new \InvalidArgumentException('Item does not exist in menu');
        }

        unset($this->items[$key]);
        $this->items = array_values($this->items);
    }

    /**
     * @return MenuStyle
     */
    public function getStyle()
    {
        return $this->style;
    }

    public function getCurrentFrame()
    {
        return $this->currentFrame;
    }

    /**
     * @param string $text
     * @return Flash
     */
    public function flash($text)
    {
        if (strpos($text, "\n") !== false) {
            throw new \InvalidArgumentException;
        }

        $style = (new MenuStyle($this->terminal))
            ->setBg('yellow')
            ->setFg('red');

        return new Flash($this, $style, $this->terminal, $text);
    }

    /**
     * @param string $text
     * @return Confirm
     */
    public function confirm($text)
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
