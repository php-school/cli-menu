<?php

namespace PhpSchool\CliMenu\Util;

/**
 * Class StringUtil
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class StringUtil
{
    /**
     * Minimal multibyte wordwrap implementation
     * which also takes break lenght into consideration
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
}
