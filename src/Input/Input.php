<?php

namespace PhpSchool\CliMenu\Input;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
interface Input
{
    public function ask() : InputResult;

    public function validate(string $input) : bool;

    public function setPromptText(string $promptText) : Input;

    public function getPromptText() : string;

    public function setValidationFailedText(string $validationFailedText) : Input;

    public function getValidationFailedText() : string;

    public function setPlaceholderText(string $placeholderText) : Input;

    public function getPlaceholderText() : string;

    public function format(string $value) : string;
}
