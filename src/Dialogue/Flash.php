<?php

namespace PhpSchool\CliMenu\Dialogue;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class Flash extends Dialogue
{
    /**
     * Flash a message on top of the menu which
     * disappears on any keystroke.
     */
    public function display()
    {
        $this->assertMenuOpen();

        $this->terminal->moveCursorToRow($this->y);

        $this->emptyRow();

        $this->write(sprintf(
            "%s  %s  %s\n",
            $this->style->getUnselectedSetCode(),
            $this->text,
            $this->style->getUnselectedUnsetCode()
        ));

        $this->emptyRow();
        $this->terminal->moveCursorToTop();
        $this->terminal->getKeyedInput();
        $this->parentMenu->redraw();
    }
}
