<?php

namespace PhpSchool\CliMenu\Style;

class Colour
{
    public const AVAILABLE_FOREGROUND_COLOURS = [
        'black'   => 30,
        'red'     => 31,
        'green'   => 32,
        'yellow'  => 33,
        'blue'    => 34,
        'magenta' => 35,
        'cyan'    => 36,
        'white'   => 37,
        'default' => 39,
    ];

    public const AVAILABLE_BACKGROUND_COLOURS = [
        'black'   => 40,
        'red'     => 41,
        'green'   => 42,
        'yellow'  => 43,
        'blue'    => 44,
        'magenta' => 45,
        'cyan'    => 46,
        'white'   => 47,
        'default' => 49,
    ];

    public const INVERTED_SET_CODE = "\033[7m";

    public const INVERTED_UNSET_CODE = "\033[27m";

    public const RESET_CODE = "\033[0m";
}
