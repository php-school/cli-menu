<?php

namespace PhpSchool\CliMenu\IO;

use function is_resource;
use function get_resource_type;
use function stream_get_meta_data;
use function strpos;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class ResourceOutputStream implements OutputStream
{
    /**
     * @var resource
     */
    private $stream;

    public function __construct($stream = null)
    {
        if ($stream === null) {
            $stream = STDOUT;
        }

        if (!is_resource($stream) || get_resource_type($stream) !== 'stream') {
            throw new \InvalidArgumentException('Expected a valid stream');
        }

        $meta = stream_get_meta_data($stream);
        if (strpos($meta['mode'], 'r') !== false && strpos($meta['mode'], '+') === false) {
            throw new \InvalidArgumentException('Expected a writable stream');
        }

        $this->stream = $stream;
    }

    public function write(string $buffer): void
    {
        fwrite($this->stream, $buffer);
    }
}
