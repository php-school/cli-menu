<?php

namespace PhpSchool\CliMenu\Input;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class Number implements Input
{
    /**
     * @var InputIO
     */
    private $inputIO;

    /**
     * @var string
     */
    private $promptText = 'Enter a number:';

    /**
     * @var string
     */
    private $validationFailedText = 'Not a valid number, try again';

    /**
     * @var string
     */
    private $placeholderText = '';

    public function __construct(InputIO $inputIO)
    {
        $this->inputIO = $inputIO;
    }

    public function setPromptText(string $promptText) : Input
    {
        $this->promptText = $promptText;

        return $this;
    }

    public function getPromptText() : string
    {
        return $this->promptText;
    }

    public function setValidationFailedText(string $validationFailedText) : Input
    {
        $this->validationFailedText = $validationFailedText;

        return $this;
    }

    public function getValidationFailedText() : string
    {
        return $this->validationFailedText;
    }

    public function setPlaceholderText(string $placeholderText) : Input
    {
        $this->placeholderText = $placeholderText;

        return $this;
    }

    public function getPlaceholderText() : string
    {
        return $this->placeholderText;
    }

    public function ask() : InputResult
    {
        $this->inputIO->registerInputMap("\033[A", 'up');
        $this->inputIO->registerInputMap("\033[B", 'down');

        $this->inputIO->registerControlCallback('up', function (InputIO $inputIO, string $input) {
            return $this->validate($input) ? $input + 1 : $input;
        });

        $this->inputIO->registerControlCallback('down', function (InputIO $inputIO, string $input) {
            return $this->validate($input) ? $input - 1 : $input;
        });

        return $this->inputIO->collect($this);
    }

    public function validate(string $input) : bool
    {
        return (bool) preg_match('/^\d+$/', $input);
    }

    public function format(string $value) : string
    {
        return $value;
    }
}
