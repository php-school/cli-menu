<?php

namespace PhpSchool\CliMenu\IO;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
interface InputStream
{
    /**
     * Callback should be called with the number of bytes requested
     * when ready.
     */
    public function read(int $numBytes, callable $callback) : void;
}
