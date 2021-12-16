<?php
declare(strict_types=1);

namespace PhpSchool\CliMenu\Input;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class InputResult
{
    /**
     * @var string
     */
    private $input;

    public function __construct(string $input)
    {
        $this->input = $input;
    }

    public function fetch() : string
    {
        return $this->input;
    }
}
