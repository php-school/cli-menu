<?php
declare(strict_types=1);

namespace PhpSchool\CliMenu\Input;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Util\StringUtil;
use PhpSchool\Terminal\InputCharacter;
use PhpSchool\Terminal\NonCanonicalReader;
use PhpSchool\Terminal\Terminal;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class InputIO
{
    /**
     * @var CliMenu
     */
    private $parentMenu;

    /**
     * @var Terminal
     */
    private $terminal;

    /**
     * @var callable[][]
     */
    private $callbacks = [];

    public function __construct(CliMenu $parentMenu, Terminal $terminal)
    {
        $this->terminal     = $terminal;
        $this->parentMenu   = $parentMenu;
    }

    public function collect(Input $input) : InputResult
    {
        $this->drawInput($input, $input->getPlaceholderText());

        $inputValue = $input->getPlaceholderText();
        $havePlaceHolderValue = !empty($inputValue);
        
        $originalValue = $inputValue;

        $reader = new NonCanonicalReader($this->terminal);

        while ($char = $reader->readCharacter()) {
            if ($char->isNotControl()) {
                if ($havePlaceHolderValue) {
                    $inputValue = $char->get();
                    $havePlaceHolderValue = false;
                } else {
                    $inputValue .= $char->get();
                }

                $this->parentMenu->redraw();
                $this->drawInput($input, $inputValue);
                continue;
            }

            if ($char->isHandledControl()) {
                switch ($char->getControl()) {
                    case InputCharacter::ESC:
                        $this->parentMenu->redraw();
                        return new InputResult($originalValue);
                    case InputCharacter::ENTER:
                        if ($input->validate($inputValue)) {
                            $this->parentMenu->redraw();
                            return new InputResult($inputValue);
                        } else {
                            $this->drawInputWithError($input, $inputValue);
                            continue 2;
                        }

                    case InputCharacter::BACKSPACE:
                        $inputValue = substr($inputValue, 0, -1);
                        $this->parentMenu->redraw();
                        $this->drawInput($input, $inputValue);
                        continue 2;
                }

                if (!empty($this->callbacks[$char->getControl()])) {
                    foreach ($this->callbacks[$char->getControl()] as $callback) {
                        $inputValue = $callback($inputValue);
                        $this->drawInput($input, $inputValue);
                    }
                }
            }
        }
    }

    public function registerControlCallback(string $control, callable $callback) : void
    {
        if (!isset($this->callbacks[$control])) {
            $this->callbacks[$control] = [];
        }

        $this->callbacks[$control][] = $callback;
    }

    private function getInputWidth(array $lines) : int
    {
        return max(
            array_map(
                function (string $line) {
                    return mb_strlen($line);
                },
                $lines
            )
        ) ? : 0;
    }

    private function calculateYPosition() : int
    {
        $lines = 5; //1. empty 2. prompt text 3. empty 4. input 5. empty

        return (int) (ceil($this->parentMenu->getCurrentFrame()->count() / 2) - ceil($lines /2) + 1);
    }

    private function calculateYPositionWithError() : int
    {
        $lines = 7; //1. empty 2. prompt text 3. empty 4. input 5. empty 6. error 7. empty

        return (int) (ceil($this->parentMenu->getCurrentFrame()->count() / 2) - ceil($lines /2) + 1);
    }

    private function calculateXPosition(Input $input, string $userInput) : int
    {
        $width = $this->getInputWidth(
            [
                $input->getPromptText(),
                $input->getValidationFailedText(),
                $userInput
            ]
        );

        $parentStyle     = $this->parentMenu->getStyle();
        $halfWidth       = ($width + ($input->getStyle()->getPaddingLeftRight() * 2)) / 2;
        $parentHalfWidth = ceil($parentStyle->getWidth() / 2 + $parentStyle->getMargin());

        return (int) ($parentHalfWidth - $halfWidth);
    }

    private function drawLine(Input $input, string $userInput, string $text) : void
    {
        $this->terminal->moveCursorToColumn($this->calculateXPosition($input, $userInput));

        $line = sprintf(
            "%s%s%s%s%s\n",
            $input->getStyle()->getColoursSetCode(),
            str_repeat(' ', $input->getStyle()->getPaddingLeftRight()),
            $text,
            str_repeat(' ', $input->getStyle()->getPaddingLeftRight()),
            $input->getStyle()->getColoursResetCode()
        );

        $this->terminal->write($line);
    }

    private function drawCenteredLine(Input $input, string $userInput, string $text) : void
    {
        $width = $this->getInputWidth(
            [
                $input->getPromptText(),
                $input->getValidationFailedText(),
                $userInput
            ]
        );

        $textLength = mb_strlen(StringUtil::stripAnsiEscapeSequence($text));
        $leftFill   = (int) (($width / 2) - ($textLength / 2));
        $rightFill  = (int) ceil($width - $leftFill - $textLength);

        $this->drawLine(
            $input,
            $userInput,
            sprintf(
                '%s%s%s',
                str_repeat(' ', $leftFill),
                $text,
                str_repeat(' ', $rightFill)
            )
        );
    }

    private function drawEmptyLine(Input $input, string $userInput) : void
    {
        $width = $this->getInputWidth(
            [
                $input->getPromptText(),
                $input->getValidationFailedText(),
                $userInput
            ]
        );

        $this->drawLine(
            $input,
            $userInput,
            str_repeat(' ', $width)
        );
    }

    private function drawInput(Input $input, string $userInput) : void
    {
        $this->terminal->moveCursorToRow($this->calculateYPosition());

        $this->drawEmptyLine($input, $userInput);
        $this->drawTitle($input, $userInput);
        $this->drawEmptyLine($input, $userInput);
        $this->drawInputField($input, $input->filter($userInput));
        $this->drawEmptyLine($input, $userInput);
    }

    private function drawInputWithError(Input $input, string $userInput) : void
    {
        $this->terminal->moveCursorToRow($this->calculateYPositionWithError());

        $this->drawEmptyLine($input, $userInput);
        $this->drawTitle($input, $userInput);
        $this->drawEmptyLine($input, $userInput);
        $this->drawInputField($input, $input->filter($userInput));
        $this->drawEmptyLine($input, $userInput);
        $this->drawCenteredLine(
            $input,
            $userInput,
            $input->getValidationFailedText()
        );
        $this->drawEmptyLine($input, $userInput);
    }

    private function drawTitle(Input $input, string $userInput) : void
    {

        $this->drawCenteredLine(
            $input,
            $userInput,
            $input->getPromptText()
        );
    }

    private function drawInputField(Input $input, string $userInput) : void
    {
        $this->drawCenteredLine(
            $input,
            $userInput,
            sprintf(
                '%s%s%s',
                $input->getStyle()->getInvertedColoursSetCode(),
                $userInput,
                $input->getStyle()->getInvertedColoursUnsetCode()
            )
        );
    }
}
