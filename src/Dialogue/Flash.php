<?php
declare(strict_types=1);

namespace PhpSchool\CliMenu\Dialogue;

use PhpSchool\Terminal\NonCanonicalReader;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class Flash extends Dialogue
{
    /**
     * Flash a message on top of the menu which
     * disappears on any keystroke.
     */
    public function display() : void
    {
        $this->assertMenuOpen();

        $this->terminal->moveCursorToRow($this->y);

        $this->emptyRow();

        $this->write(sprintf(
            "%s%s%s%s%s\n",
            $this->style->getColoursSetCode(),
            str_repeat(' ', $this->style->getPaddingLeftRight()),
            $this->text,
            str_repeat(' ', $this->style->getPaddingLeftRight()),
            $this->style->getColoursResetCode()
        ));

        $this->emptyRow();

        $this->terminal->moveCursorToTop();

        $reader = new NonCanonicalReader($this->terminal);
        $reader->readCharacter();

        $this->parentMenu->redraw();
    }
}
