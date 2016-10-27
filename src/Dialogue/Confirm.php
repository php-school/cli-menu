<?php

namespace PhpSchool\CliMenu\Dialogue;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class Confirm extends Dialogue
{

    /**
     * Display confirmation with a button with the given text
     *
     * @param string $confirmText
     */
    public function display($confirmText = 'OK')
    {
        $this->assertMenuOpen();

        $this->terminal->moveCursorToRow($this->y);

        $promptWidth = mb_strlen($this->text) + 4;

        $this->emptyRow();

        $this->write(sprintf(
            "%s%s%s%s%s\n",
            $this->style->getUnselectedSetCode(),
            str_repeat(' ', $this->style->getPadding()),
            $this->text,
            str_repeat(' ', $this->style->getPadding()),
            $this->style->getUnselectedUnsetCode()
        ));

        $this->emptyRow();

        $confirmText = sprintf(' < %s > ', $confirmText);
        $leftFill = ($promptWidth / 2) - (mb_strlen($confirmText) / 2);

        $this->write(sprintf(
            '%s%s%s',
            $this->style->getUnselectedSetCode(),
            str_repeat(' ', $leftFill),
            $this->style->getUnselectedSetCode()
        ));

        $this->write(
            sprintf(
                '%s%s%s',
                $this->style->getSelectedSetCode(),
                $confirmText,
                $this->style->getSelectedUnsetCode()
            ),
            -1
        );

        $this->write(
            sprintf(
                "%s%s%s\n",
                $this->style->getUnselectedSetCode(),
                str_repeat(' ', ceil($promptWidth - $leftFill - mb_strlen($confirmText))),
                $this->style->getSelectedUnsetCode()
            ),
            -1
        );

        $this->write(sprintf(
            "%s%s%s%s%s\n",
            $this->style->getUnselectedSetCode(),
            str_repeat(' ', $this->style->getPadding()),
            str_repeat(' ', mb_strlen($this->text)),
            str_repeat(' ', $this->style->getPadding()),
            $this->style->getUnselectedUnsetCode()
        ));

        $this->terminal->moveCursorToTop();
        $input = $this->terminal->getKeyedInput();

        while ($input !== 'enter') {
            $input = $this->terminal->getKeyedInput();
        }

        $this->parentMenu->redraw();
    }
}
