<?php

namespace PhpSchool\CliMenu\IO;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
interface OutputStream
{
    public function write(string $buffer) : void;
}
