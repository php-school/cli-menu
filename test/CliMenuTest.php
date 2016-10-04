<?php

namespace PhpSchool\CliMenuTest;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuStyle;
use PHPUnit_Framework_TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class CliMenuTest extends PHPUnit_Framework_TestCase
{
    public function testGetMenuStyle()
    {
        $menu = new CliMenu('PHP School FTW', []);
        static::assertInstanceOf(MenuStyle::class, $menu->getStyle());

        $style = new MenuStyle();
        $menu = new CliMenu('PHP School FTW', [], null, $style);
        static::assertSame($style, $menu->getStyle());
    }
}
