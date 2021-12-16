<?php
declare(strict_types=1);

namespace PhpSchool\CliMenu\Dialogue;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Exception\MenuNotOpenException;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\Terminal\Terminal;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
abstract class Dialogue
{
    /**
     * @var MenuStyle
     */
    protected $style;

    /**
     * @var CliMenu
     */
    protected $parentMenu;

    /**
     * @var Terminal
     */
    protected $terminal;

    /**
     * @var string $text
     */
    protected $text;

    /**
     * @var bool $cancellable
     */
    protected $cancellable;

    /**
     * @var int
     */
    protected $x;

    /**
     * @var int
     */
    protected $y;

    public function __construct(
        CliMenu $parentMenu,
        MenuStyle $menuStyle,
        Terminal $terminal,
        string $text
    ) {
        $this->style        = $menuStyle;
        $this->terminal     = $terminal;
        $this->text         = $text;
        $this->parentMenu   = $parentMenu;

        $this->calculateCoordinates();
    }

    /**
     * @throws MenuNotOpenException
     */
    protected function assertMenuOpen() : void
    {
        if (!$this->parentMenu->isOpen()) {
            throw new MenuNotOpenException;
        }
    }

    /**
     * Calculate the co-ordinates to write the messages
     */
    protected function calculateCoordinates() : void
    {
        //y
        $textLines = count(explode("\n", $this->text)) + 2;
        $this->y = (int) (ceil($this->parentMenu->getCurrentFrame()->count() / 2) - ceil($textLines / 2) + 1);

        //x
        $parentStyle = $this->parentMenu->getStyle();
        $dialogueHalfLength = (int) ((mb_strlen($this->text) + ($this->style->getPaddingLeftRight() * 2)) / 2);
        $widthHalfLength = (int) ceil($parentStyle->getWidth() / 2 + $parentStyle->getMargin());
        $this->x = $widthHalfLength - $dialogueHalfLength;
    }

    /**
     * Write an empty row
     */
    protected function emptyRow() : void
    {
        $this->write(
            sprintf(
                "%s%s%s%s%s\n",
                $this->style->getColoursSetCode(),
                str_repeat(' ', $this->style->getPaddingLeftRight()),
                str_repeat(' ', mb_strlen($this->text)),
                str_repeat(' ', $this->style->getPaddingLeftRight()),
                $this->style->getColoursResetCode()
            )
        );
    }

    /**
     * Write some text at a particular column
     */
    protected function write(string $text, int $column = null) : void
    {
        $this->terminal->moveCursorToColumn($column ?: $this->x);
        $this->terminal->write($text);
    }

    public function getStyle() : MenuStyle
    {
        return $this->style;
    }
}
