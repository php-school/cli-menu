<?php
declare(strict_types=1);

namespace PhpSchool\CliMenu\Input;

use PhpSchool\CliMenu\MenuStyle;

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

    public function filter(string $value) : string;

    public function getStyle() : MenuStyle;
}
