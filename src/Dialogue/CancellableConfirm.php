<?php
declare(strict_types=1);

namespace PhpSchool\CliMenu\Dialogue;

use PhpSchool\Terminal\InputCharacter;
use PhpSchool\Terminal\NonCanonicalReader;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class CancellableConfirm extends Dialogue
{
    /**
     * @var bool
     */
    private $confirm = true;

    /**
     * Display confirmation with a button with the given text
     */
    public function display(string $confirmText = 'OK', string $cancelText = 'Cancel') : bool
    {
        $this->drawDialog($confirmText, $cancelText);

        $reader = new NonCanonicalReader($this->terminal);

        while ($char = $reader->readCharacter()) {
            if ($char->isControl() && $char->getControl() === InputCharacter::ENTER) {
                $this->parentMenu->redraw();
                return $this->confirm;
            } elseif ($char->isControl() && $char->getControl() === InputCharacter::TAB ||
                ($char->isControl() && $char->getControl() === InputCharacter::RIGHT && $this->confirm) ||
                ($char->isControl() && $char->getControl() === InputCharacter::LEFT && !$this->confirm)
            ) {
                $this->confirm = !$this->confirm;
                $this->drawDialog($confirmText, $cancelText);
            }
        }
    }

    private function drawDialog(string $confirmText = 'OK', string $cancelText = 'Cancel'): void
    {
        $this->assertMenuOpen();

        $this->terminal->moveCursorToRow($this->y);

        $promptWidth = mb_strlen($this->text) + 4;

        $buttonLength = mb_strlen($confirmText) + 6;
        $buttonLength += mb_strlen($cancelText) + 7;

        $confirmButton = sprintf(
            '%s%s < %s > %s%s',
            $this->style->getOptionCode($this->confirm ? 'bold' : 'dim'),
            $this->style->getInvertedColoursSetCode(),
            $confirmText,
            $this->style->getInvertedColoursUnsetCode(),
            $this->style->getOptionCode($this->confirm ? 'bold' : 'dim', false)
        );

        $cancelButton = sprintf(
            '%s%s < %s > %s%s',
            $this->style->getOptionCode($this->confirm ? 'dim' : 'bold'),
            $this->style->getInvertedColoursSetCode(),
            $cancelText,
            $this->style->getInvertedColoursUnsetCode(),
            $this->style->getOptionCode($this->confirm ? 'dim' : 'bold', false)
        );

        $buttonRow = $confirmButton . " " . $cancelButton;

        if ($promptWidth < $buttonLength) {
            $pad = ($buttonLength - $promptWidth) / 2;
            $this->text = sprintf(
                '%s%s%s',
                str_repeat(' ', intval(round($pad, 0, 2) + $this->style->getPaddingLeftRight())),
                $this->text,
                str_repeat(' ', intval(round($pad, 0, 1) + $this->style->getPaddingLeftRight()))
            );
            $promptWidth = mb_strlen($this->text) + 4;
        }

        $leftFill = (int) (($promptWidth / 2) - ($buttonLength / 2));

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

        $this->write(sprintf(
            "%s%s%s%s%s\n",
            $this->style->getColoursSetCode(),
            str_repeat(' ', $leftFill),
            $buttonRow,
            str_repeat(' ', (int) ceil($promptWidth - $leftFill - $buttonLength)),
            $this->style->getColoursResetCode()
        ));

        $this->emptyRow();

        $this->terminal->moveCursorToTop();
    }
}
