<?php
declare(strict_types=1);

namespace PhpSchool\CliMenu\Input;

use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\Terminal\InputCharacter;

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

    /**
     * @var null|callable
     */
    private $validator;

    /**
     * @var MenuStyle
     */
    private $style;

    public function __construct(InputIO $inputIO, MenuStyle $style)
    {
        $this->inputIO = $inputIO;
        $this->style = $style;
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

    public function setValidator(callable $validator) : Input
    {
        $this->validator = $validator;
        
        return $this;
    }

    public function ask() : InputResult
    {
        $this->inputIO->registerControlCallback(InputCharacter::UP, function (string $input) {
            return $this->validate($input) ? (string) ((int) $input + 1) : $input;
        });

        $this->inputIO->registerControlCallback(InputCharacter::DOWN, function (string $input) {
            return $this->validate($input) ? (string) ((int) $input - 1) : $input;
        });

        return $this->inputIO->collect($this);
    }

    public function validate(string $input) : bool
    {
        if ($this->validator) {
            $validator = $this->validator;
            
            if ($validator instanceof \Closure) {
                $validator = $validator->bindTo($this);
            }
            
            return $validator($input);
        }

        return (bool) preg_match('/^-?\d+$/', $input);
    }

    public function filter(string $value) : string
    {
        return $value;
    }

    public function getStyle() : MenuStyle
    {
        return $this->style;
    }
}
