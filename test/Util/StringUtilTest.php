<?php

namespace PhpSchool\CliMenuTest\Util;

use PhpSchool\CliMenu\Util\StringUtil;
use PHPUnit\Framework\TestCase;

/**
 * Class StringUtilTest
 * @package PhpSchool\CliMenuTest\MenuItem
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class StringUtilTest extends TestCase
{
    protected $dummyText;

    public function testItWrapsAsExpectedTo80Length()
    {

        $result = StringUtil::wordwrap($this->dummyText, 80);
        $expected = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor \n" .
            "incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud \n" .
            "exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor \n" .
            "in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. \n" .
            "Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt \n" .
            "mollit anim id est laborum";

        $this->assertEquals($result, $expected);
    }

    public function testItWrapsAsExpectedTo60Length()
    {

        $result = StringUtil::wordwrap($this->dummyText, 60);
        $expected = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, \n" .
            "sed do eiusmod tempor incididunt ut labore et dolore magna \n" .
            "aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco \n" .
            "laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure \n" .
            "dolor in reprehenderit in voluptate velit esse cillum dolore eu \n" .
            "fugiat nulla pariatur. Excepteur sint occaecat cupidatat non \n" .
            "proident, sunt in culpa qui officia deserunt mollit anim id est \n" .
            "laborum";

        $this->assertEquals($result, $expected);
    }

    public function testItCanUseACustomBreakCharacter()
    {

        $result = StringUtil::wordwrap($this->dummyText, 60, 'H');
        $expected = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, H" .
            "sed do eiusmod tempor incididunt ut labore et dolore magna H" .
            "aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco H" .
            "laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure H" .
            "dolor in reprehenderit in voluptate velit esse cillum dolore eu H" .
            "fugiat nulla pariatur. Excepteur sint occaecat cupidatat non H" .
            "proident, sunt in culpa qui officia deserunt mollit anim id est H" .
            "laborum";

        $this->assertEquals($result, $expected);
    }

    public function testItCanStripAnsiEscapeSequence()
    {
        $result = StringUtil::stripAnsiEscapeSequence("\x1b[7mfoo\x1b[0m");
        $this->assertEquals('foo', $result);

        $result = StringUtil::stripAnsiEscapeSequence("foobar\x1b[00m\x1b[01;31m");
        $this->assertEquals('foobar', $result);

        $result = StringUtil::stripAnsiEscapeSequence("foo\x1b[00mbar\x1b[01;31mbaz\x1b[00m!!!\x1b[01;31m");
        $this->assertEquals('foobarbaz!!!', $result);
    }

    protected function setup()
    {
        parent::setup();

        $this->dummyText = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor ' .
            'incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud ' .
            'exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor ' .
            'in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. ' .
            'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt ' .
            'mollit anim id est laborum';
    }
}
