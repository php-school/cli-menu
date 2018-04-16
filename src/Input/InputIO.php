<?php

namespace PhpSchool\CliMenu\Input;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Terminal\TerminalInterface;
use PhpSchool\CliMenu\Util\StringUtil;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class InputIO
{
    /**
     * @var MenuStyle
     */
    private $style;

    /**
     * @var CliMenu
     */
    private $parentMenu;

    /**
     * @var TerminalInterface
     */
    private $terminal;

    /**
     * @var array
     */
    private $inputMap = [
        "\n"   => 'enter',
        "\r"   => 'enter',
        "\177" => 'backspace'
    ];

    /**
     * @var callable[][]
     */
    private $callbacks = [];

    public function __construct(CliMenu $parentMenu, MenuStyle $menuStyle, TerminalInterface $terminal)
    {
        $this->style        = $menuStyle;
        $this->terminal     = $terminal;
        $this->parentMenu   = $parentMenu;
    }

    public function collect(Input $input) : InputResult
    {
        $this->drawInput($input, $input->getPlaceholderText());

        $inputValue = $input->getPlaceholderText();

        while (($userInput = $this->terminal->getKeyedInput($this->inputMap)) !== null) {
            $this->parentMenu->redraw();
            $this->drawInput($input, $inputValue);

            if ($userInput === 'enter') {
                if ($input->validate($inputValue)) {
                    $this->parentMenu->redraw();
                    return new InputResult($inputValue);
                } else {
                    $this->drawInputWithError($input, $inputValue);
                    continue;
                }
            }

            if ($userInput === 'backspace') {
                $inputValue = substr($inputValue, 0, -1);
                $this->drawInput($input, $inputValue);
                continue;
            }

            if (!empty($this->callbacks[$userInput])) {
                foreach ($this->callbacks[$userInput] as $callback) {
                    $inputValue = $callback($this, $inputValue);
                    $this->drawInput($input, $inputValue);
                }
                continue;
            }

            $inputValue .= $userInput;
            $this->drawInput($input, $inputValue);
        }
    }

    public function registerInputMap(string $input, string $mapTo) : void
    {
        $this->inputMap[$input] = $mapTo;
    }

    public function registerControlCallback(string $control, callable $callback) : void
    {
        if (!isset($this->callbacks[$control])) {
            $this->callbacks[$control] = [];
        }

        $this->callbacks[$control][] = $callback;
    }

    private function getInputWidth(array $lines)
    {
        return max(
            array_map(
                function (string $line) {
                    return mb_strlen($line);
                },
                $lines
            )
        );
    }

    private function calculateYPosition() : int
    {
        $lines = 5; //1. empty 2. prompt text 3. empty 4. input 5. empty

        return ceil($this->parentMenu->getCurrentFrame()->count() / 2) - ceil($lines /2) + 1;
    }

    private function calculateYPositionWithError() : int
    {
        $lines = 7; //1. empty 2. prompt text 3. empty 4. input 5. empty 6. error 7. empty

        return ceil($this->parentMenu->getCurrentFrame()->count() / 2) - ceil($lines /2) + 1;
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
        $halfWidth       = ($width + ($this->style->getPadding() * 2)) / 2;
        $parentHalfWidth = ceil($parentStyle->getWidth() / 2);

        return $parentHalfWidth - $halfWidth;
    }

    private function drawLine(Input $input, string $userInput, string $text) : void
    {
        $this->terminal->moveCursorToColumn($this->calculateXPosition($input, $userInput));

        printf(
            "%s%s%s%s%s\n",
            $this->style->getUnselectedSetCode(),
            str_repeat(' ', $this->style->getPadding()),
            $text,
            str_repeat(' ', $this->style->getPadding()),
            $this->style->getUnselectedUnsetCode()
        );
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
        $leftFill   = ($width / 2) - ($textLength / 2);
        $rightFill  = ceil($width - $leftFill - $textLength);

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
        $this->drawInputField($input, $input->format($userInput));
        $this->drawEmptyLine($input, $userInput);
    }

    private function drawInputWithError(Input $input, string $userInput) : void
    {
        $this->terminal->moveCursorToRow($this->calculateYPositionWithError());

        $this->drawEmptyLine($input, $userInput);
        $this->drawTitle($input, $userInput);
        $this->drawEmptyLine($input, $userInput);
        $this->drawInputField($input, $input->format($userInput));
        $this->drawEmptyLine($input, $userInput);
        $this->drawCenteredLine(
            $input,
            $userInput,
            sprintf(
                '%s',
                $input->getValidationFailedText()
            )
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
                '%s%s%s%s%s',
                $this->style->getUnselectedUnsetCode(),
                $this->style->getSelectedSetCode(),
                $userInput,
                $this->style->getSelectedUnsetCode(),
                $this->style->getUnselectedSetCode()
            )
        );
    }
}
