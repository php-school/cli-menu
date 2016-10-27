<?php

namespace PhpSchool\CliMenu\Dialogue;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Exception\MenuNotOpenException;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Terminal\TerminalInterface;

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
     * @var TerminalInterface
     */
    protected $terminal;

    /**
     * @var string $text
     */
    protected $text;

    /**
     * @var int
     */
    protected $x;

    /**
     * @var int
     */
    protected $y;

    /**
     * @param CliMenu $parentMenu
     * @param MenuStyle $menuStyle
     * @param TerminalInterface $terminal
     * @param string $text
     */
    public function __construct(CliMenu $parentMenu, MenuStyle $menuStyle, TerminalInterface $terminal, $text)
    {
        $this->style        = $menuStyle;
        $this->terminal     = $terminal;
        $this->text         = $text;
        $this->parentMenu   = $parentMenu;

        $this->calculateCoordinates();
    }

    /**
     * @throws MenuNotOpenException
     */
    protected function assertMenuOpen()
    {
        if (!$this->parentMenu->isOpen()) {
            throw new MenuNotOpenException;
        }
    }

    /**
     * Calculate the co-ordinates to write the messages
     */
    protected function calculateCoordinates()
    {
        //y
        $textLines          = count(explode("\n", $this->text)) + 2;
        $this->y            = ceil($this->parentMenu->getCurrentFrame()->count() / 2) - ceil($textLines / 2) + 1;

        //x
        $parentStyle        = $this->parentMenu->getStyle();
        $dialogueHalfLength = (mb_strlen($this->text) + ($this->style->getPadding() * 2)) / 2;
        $widthHalfLength    = ceil($parentStyle->getWidth() / 2);
        $this->x            = $widthHalfLength - $dialogueHalfLength;
    }

    /**
     * Write an empty row
     */
    protected function emptyRow()
    {
        $this->write(
            sprintf(
                "%s%s%s%s%s\n",
                $this->style->getUnselectedSetCode(),
                str_repeat(' ', $this->style->getPadding()),
                str_repeat(' ', mb_strlen($this->text)),
                str_repeat(' ', $this->style->getPadding()),
                $this->style->getUnselectedUnsetCode()
            )
        );
    }

    /**
     * Write some text at a particular column
     *
     * @param int $column
     * @param string $text
     */
    protected function write($text, $column = null)
    {
        $this->terminal->moveCursorToColumn($column ?: $this->x);
        echo $text;
    }

    /**
     * @return MenuStyle
     */
    public function getStyle()
    {
        return $this->style;
    }
}
