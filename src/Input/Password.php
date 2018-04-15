<?php

namespace PhpSchool\CliMenu\Input;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class Password implements Input
{
    /**
     * @var InputIO
     */
    private $inputIO;

    /**
     * @var string
     */
    private $promptText = 'Enter password:';

    /**
     * @var string
     */
    private $validationFailedText = 'Invalid password, try again';

    /**
     * @var string
     */
    private $placeholderText = '';

    /**
     * @var null|callable
     */
    private $validator;

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

    public function setValidator(callable $validator)
    {
        $this->validator = $validator;
    }

    public function ask() : InputResult
    {
        return $this->inputIO->collect($this);
    }

    public function validate(string $input) : bool
    {
        if ($this->validator) {
            $validator = $this->validator;
            return $validator($input);
        }

        return mb_strlen($input) > 16;
    }

    public function format(string $value) : string
    {
        return str_repeat('*', mb_strlen($value));
    }
}
