<?php

namespace PhpSchool\CliMenu\Util;

/**
 * Class StringUtil
 *
 * @package PhpSchool\CliMenu\Util
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class StringUtil
{
    /**
     * Minimal multi-byte wordwrap implementation
     * which also takes break length into consideration
     *
     * @param string $str
     * @param int $width
     * @param string $break
     * @return string
     */
    public static function wordwrap($str, $width, $break = "\n")
    {
        $length = 0;
        return implode(" ", array_map(function ($word) use (&$length, $width, $break) {
            $length += (mb_strlen($word) + 1);

            if ($length > $width) {
                $length = mb_strlen($break);
                return sprintf("%s%s", $break, $word);
            }

            return $word;
        }, explode(" ", $str)));
    }

    /**
     * @param string $str
     * @return string
     */
    public static function stripAnsiEscapeSequence($str)
    {
        return preg_replace('/\x1b[^m]*m/', '', $str);
    }
}
