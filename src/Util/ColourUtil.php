<?php
declare(strict_types=1);

namespace PhpSchool\CliMenu\Util;

use Assert\Assertion;
use PhpSchool\Terminal\Terminal;

class ColourUtil
{
    /**
     * @var array
     */
    private static $defaultColoursNames = [
        'black',
        'red',
        'green',
        'yellow',
        'blue',
        'magenta',
        'cyan',
        'white',
        'default',
    ];

    /**
     * @var array
     */
    private static $coloursMap = [
        0 => 'black',
        1 => 'red',
        2 => 'green',
        3 => 'yellow',
        4 => 'blue',
        5 => 'magenta',
        6 => 'cyan',
        7 => 'white',
        8 => 'black',
        9 => 'red',
        10 => 'green',
        11 => 'yellow',
        12 => 'blue',
        13 => 'magenta',
        14 => 'cyan',
        15 => 'white',
        16 => 'black',
        17 => 'blue',
        18 => 'blue',
        19 => 'blue',
        20 => 'blue',
        21 => 'blue',
        22 => 'green',
        23 => 'cyan',
        24 => 'cyan',
        25 => 'blue',
        26 => 'blue',
        27 => 'blue',
        28 => 'green',
        29 => 'green',
        30 => 'cyan',
        31 => 'cyan',
        32 => 'blue',
        33 => 'blue',
        34 => 'green',
        35 => 'green',
        36 => 'green',
        37 => 'cyan',
        38 => 'cyan',
        39 => 'cyan',
        40 => 'green',
        41 => 'green',
        42 => 'green',
        43 => 'cyan',
        44 => 'cyan',
        45 => 'cyan',
        46 => 'green',
        47 => 'green',
        48 => 'green',
        49 => 'green',
        50 => 'cyan',
        51 => 'cyan',
        52 => 'red',
        53 => 'blue',
        54 => 'blue',
        55 => 'blue',
        56 => 'blue',
        57 => 'blue',
        58 => 'yellow',
        59 => 'black',
        60 => 'blue',
        61 => 'blue',
        62 => 'blue',
        63 => 'blue',
        64 => 'green',
        65 => 'green',
        66 => 'cyan',
        67 => 'cyan',
        68 => 'blue',
        69 => 'blue',
        70 => 'green',
        71 => 'green',
        72 => 'green',
        73 => 'cyan',
        74 => 'cyan',
        75 => 'cyan',
        76 => 'green',
        77 => 'green',
        78 => 'green',
        79 => 'green',
        80 => 'cyan',
        81 => 'cyan',
        82 => 'green',
        83 => 'green',
        84 => 'green',
        85 => 'green',
        86 => 'cyan',
        87 => 'cyan',
        88 => 'red',
        89 => 'magenta',
        90 => 'magenta',
        91 => 'magenta',
        92 => 'blue',
        93 => 'blue',
        94 => 'yellow',
        95 => 'red',
        96 => 'magenta',
        97 => 'magenta',
        98 => 'blue',
        99 => 'blue',
        100 => 'yellow',
        101 => 'yellow',
        102 => 'white',
        103 => 'blue',
        104 => 'blue',
        105 => 'blue',
        106 => 'green',
        107 => 'green',
        108 => 'green',
        109 => 'cyan',
        110 => 'cyan',
        111 => 'cyan',
        112 => 'green',
        113 => 'green',
        114 => 'green',
        115 => 'green',
        116 => 'cyan',
        117 => 'cyan',
        118 => 'green',
        119 => 'green',
        120 => 'green',
        121 => 'green',
        122 => 'green',
        123 => 'cyan',
        124 => 'red',
        125 => 'magenta',
        126 => 'magenta',
        127 => 'magenta',
        128 => 'magenta',
        129 => 'magenta',
        130 => 'red',
        131 => 'red',
        132 => 'magenta',
        133 => 'magenta',
        134 => 'magenta',
        135 => 'magenta',
        136 => 'yellow',
        137 => 'red',
        138 => 'red',
        139 => 'magenta',
        140 => 'magenta',
        141 => 'magenta',
        142 => 'yellow',
        143 => 'yellow',
        144 => 'yellow',
        145 => 'white',
        146 => 'white',
        147 => 'white',
        148 => 'yellow',
        149 => 'green',
        150 => 'green',
        151 => 'green',
        152 => 'cyan',
        153 => 'white',
        154 => 'green',
        155 => 'green',
        156 => 'green',
        157 => 'green',
        158 => 'green',
        159 => 'cyan',
        160 => 'red',
        161 => 'magenta',
        162 => 'magenta',
        163 => 'magenta',
        164 => 'magenta',
        165 => 'magenta',
        166 => 'red',
        167 => 'red',
        168 => 'magenta',
        169 => 'magenta',
        170 => 'magenta',
        171 => 'magenta',
        172 => 'red',
        173 => 'red',
        174 => 'red',
        175 => 'magenta',
        176 => 'magenta',
        177 => 'magenta',
        178 => 'yellow',
        179 => 'yellow',
        180 => 'white',
        181 => 'white',
        182 => 'magenta',
        183 => 'magenta',
        184 => 'yellow',
        185 => 'yellow',
        186 => 'yellow',
        187 => 'yellow',
        188 => 'white',
        189 => 'white',
        190 => 'yellow',
        191 => 'yellow',
        192 => 'green',
        193 => 'green',
        194 => 'green',
        195 => 'cyan',
        196 => 'red',
        197 => 'red',
        198 => 'magenta',
        199 => 'magenta',
        200 => 'magenta',
        201 => 'magenta',
        202 => 'red',
        203 => 'red',
        204 => 'magenta',
        205 => 'magenta',
        206 => 'magenta',
        207 => 'magenta',
        208 => 'red',
        209 => 'red',
        210 => 'red',
        211 => 'magenta',
        212 => 'magenta',
        213 => 'magenta',
        214 => 'red',
        215 => 'white',
        216 => 'red',
        217 => 'red',
        218 => 'magenta',
        219 => 'magenta',
        220 => 'yellow',
        221 => 'yellow',
        222 => 'yellow',
        223 => 'white',
        224 => 'white',
        225 => 'magenta',
        226 => 'yellow',
        227 => 'yellow',
        228 => 'yellow',
        229 => 'yellow',
        230 => 'yellow',
        231 => 'white',
        232 => 'black',
        233 => 'black',
        234 => 'black',
        235 => 'black',
        236 => 'black',
        237 => 'black',
        238 => 'black',
        239 => 'black',
        240 => 'black',
        241 => 'black',
        242 => 'black',
        243 => 'black',
        244 => 'white',
        245 => 'white',
        246 => 'white',
        247 => 'white',
        248 => 'white',
        249 => 'white',
        250 => 'white',
        251 => 'white',
        252 => 'white',
        253 => 'white',
        254 => 'white',
        255 => 'white',
    ];

    public static function getDefaultColourNames() : array
    {
        return self::$defaultColoursNames;
    }

    /**
     * Simple function to transform a 8-bit (256 colours) colour code
     * to one of the default 8 colors available in the terminal
     */
    public static function map256To8(int $colourCode) : string
    {
        if (!isset(self::$coloursMap[$colourCode])) {
            throw new \InvalidArgumentException('Invalid colour code');
        }

        return self::$coloursMap[$colourCode];
    }

    /**
     * Check if $colour exists
     * If it's a 256-colours code and $terminal doesn't support it, returns a fallback value
     */
    public static function validateColour(Terminal $terminal, string $colour, string $fallback = null) : string
    {
        if (!ctype_digit($colour)) {
            return self::validateColourName($colour);
        }
        
        Assertion::between($colour, 0, 255, 'Invalid colour code');
        
        if ($terminal->getColourSupport() >= 256) {
            return $colour;
        }

        if ($fallback !== null) {
            return self::validateColourName($fallback);
        }
        
        return static::map256To8((int) $colour);
    }
    
    private static function validateColourName(string $colourName) : string
    {
        Assertion::inArray($colourName, static::getDefaultColourNames());
        return $colourName;
    }
}
