<?php

namespace PhpSchool\CliMenu\Util;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class StringUtil
{
    /**
     * Minimal multi-byte wordwrap implementation
     * which also takes break length into consideration
     */
    public static function wordwrap(string $str, int $width, string $break = "\n") : string
    {
        $length = 0;
        return implode(' ', array_map(function ($word) use (&$length, $width, $break) {
            $length += (mb_strlen($word) + 1);

            if ($length > $width) {
                $length = mb_strlen($break);
                return sprintf('%s%s', $break, $word);
            }

            return $word;
        }, explode(' ', $str)));
    }

    public static function stripAnsiEscapeSequence(string $str) : string
    {
        return preg_replace('/\x1b[^m]*m/', '', $str);
    }
}
