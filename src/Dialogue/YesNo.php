<?php

namespace PhpSchool\CliMenu\Dialogue;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class YesNo extends Dialogue
{

    private $yesText = 'Yes';

    private $noText = 'No';

    private $optionValue = false;

    public function setYesText($text)
    {
        $this->yesText = $text;

        return $this;
    }

    public function setNoText($text)
    {
        $this->noText = $text;

        return $this;
    }

    public function getYesText()
    {
        return sprintf(' <%s> ', $this->yesText);
    }

    public function getNoText()
    {
        return sprintf(' <%s> ', $this->noText);
    }

    private function setOptionValue(bool $value)
    {
        $this->optionValue = $value;

        return $this;
    }

    private function getOptionValue()
    {
        return $this->optionValue;
    }

    private function displayBody()
    {
        $this->terminal->moveCursorToRow($this->y);
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

        $promptWidth = mb_strlen($this->text) + 4;
        $fillWidth = $promptWidth - (mb_strlen($this->getYesText()) + mb_strlen($this->getNoText()));
        $placeHolderWidth = 0 == ($fillWidth % 2) ? 2 : 1;
        $fillWidth = ($fillWidth - $placeHolderWidth) / 2;

        $this->write(sprintf(
            '%s%s%s',
            $this->style->getUnselectedSetCode(),
            str_repeat(' ', $fillWidth),
            $this->style->getUnselectedSetCode()
        ));
        $this->write(
            sprintf(
                '%s%s%s',
                $this->getOptionValue() ? $this->style->getSelectedSetCode() : $this->style->getUnselectedSetCode(),
                $this->getYesText(),
                $this->getOptionValue() ? $this->style->getSelectedSetCode() : $this->style->getUnselectedSetCode()
            ),
            -1
        );
        $this->write(
            sprintf(
                '%s%s%s',
                $this->style->getUnselectedSetCode(),
                str_repeat(' ', $placeHolderWidth),
                $this->style->getUnselectedSetCode()
            ),
            -1
        );
        $this->write(
            sprintf(
                '%s%s%s',
                $this->getOptionValue() ? $this->style->getUnselectedSetCode() : $this->style->getSelectedSetCode(),
                $this->getNoText(),
                $this->getOptionValue() ? $this->style->getUnselectedSetCode() : $this->style->getSelectedSetCode()
            ),
            -1
        );
        $this->write(sprintf(
            "%s%s%s\n",
            $this->style->getUnselectedSetCode(),
            str_repeat(' ', $fillWidth),
            $this->style->getUnselectedSetCode()
        ), -1);

        $this->write(sprintf(
            "%s%s%s%s%s\n",
            $this->style->getUnselectedSetCode(),
            str_repeat(' ', $this->style->getPadding()),
            str_repeat(' ', mb_strlen($this->text)),
            str_repeat(' ', $this->style->getPadding()),
            $this->style->getUnselectedUnsetCode()
        ));
        $this->terminal->moveCursorToTop();
    }

    /**
     *
     * The YesNO dialog box is displayed and the option value is passed to the callback function
     *
     * @param $callable
     */
    public function display($callable)
    {
        $this->assertMenuOpen();
        $this->displayBody();
        $input = $this->terminal->getKeyedInput();
        while ('enter' !== $input) {
            if (in_array($input, ['left', 'right'])) {
                $this->parentMenu->redraw();
                $this->setOptionValue('left' == $input);
                $this->displayBody();
            }
            $input = $this->terminal->getKeyedInput();
        }
        $this->parentMenu->redraw();
        $callable($this->getOptionValue());
    }
}
