<?php

declare(strict_types=1);

namespace PhpSchool\CliMenu\Input;

use PhpSchool\CliMenu\MenuStyle;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class Text implements Input
{
    private InputIO $inputIO;

    private string $promptText = 'Enter text:';

    private string $validationFailedText = 'Invalid, try again';

    private string $placeholderText = '';

    private \Closure|null $validator = null;

    private MenuStyle $style;

    public function __construct(InputIO $inputIO, MenuStyle $style)
    {
        $this->inputIO = $inputIO;
        $this->style = $style;
    }

    public function setPromptText(string $promptText): Input
    {
        $this->promptText = $promptText;

        return $this;
    }

    public function getPromptText(): string
    {
        return $this->promptText;
    }

    public function setValidationFailedText(string $validationFailedText): Input
    {
        $this->validationFailedText = $validationFailedText;

        return $this;
    }

    public function getValidationFailedText(): string
    {
        return $this->validationFailedText;
    }

    public function setPlaceholderText(string $placeholderText): Input
    {
        $this->placeholderText = $placeholderText;

        return $this;
    }

    public function getPlaceholderText(): string
    {
        return $this->placeholderText;
    }

    public function setValidator(callable $validator): Input
    {
        if ($validator instanceof \Closure) {
            $validator = $validator->bindTo($this);
        }

        $this->validator = $validator(...);

        return $this;
    }

    public function ask(): InputResult
    {
        return $this->inputIO->collect($this);
    }

    public function validate(string $input): bool
    {
        if ($this->validator) {
            $validator = $this->validator;

            return $validator($input);
        }

        return !empty($input);
    }

    public function filter(string $value): string
    {
        return $value;
    }

    public function getStyle(): MenuStyle
    {
        return $this->style;
    }
}
