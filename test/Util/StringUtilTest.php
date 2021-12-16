<?php
declare(strict_types=1);

namespace PhpSchool\CliMenuTest\Util;

use PhpSchool\CliMenu\Util\StringUtil;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class StringUtilTest extends TestCase
{
    protected $dummyText;

    protected function setup() : void
    {
        parent::setUp();

        $this->dummyText = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor ' .
            'incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud ' .
            'exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor ' .
            'in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. ' .
            'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt ' .
            'mollit anim id est laborum';
    }

    public function testItWrapsAsExpectedTo80Length() : void
    {

        $result = StringUtil::wordwrap($this->dummyText, 80);
        $expected = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor\n" .
            "incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis\n" .
            "nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n" .
            "Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu\n" .
            "fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in\n" .
            "culpa qui officia deserunt mollit anim id est laborum";

        self::assertEquals($expected, $result);
    }

    public function testItWrapsAsExpectedTo60Length() : void
    {

        $result = StringUtil::wordwrap($this->dummyText, 60);
        $expected = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed\n" .
            "do eiusmod tempor incididunt ut labore et dolore magna\n" .
            "aliqua. Ut enim ad minim veniam, quis nostrud exercitation\n" .
            "ullamco laboris nisi ut aliquip ex ea commodo consequat.\n" .
            "Duis aute irure dolor in reprehenderit in voluptate velit\n" .
            "esse cillum dolore eu fugiat nulla pariatur. Excepteur sint\n" .
            "occaecat cupidatat non proident, sunt in culpa qui officia\n" .
            "deserunt mollit anim id est laborum";

        self::assertEquals($expected, $result);
    }

    public function testItCanUseACustomBreakCharacter() : void
    {
        $result = StringUtil::wordwrap($this->dummyText, 60, 'H');
        
        $expected = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sedH" .
            "do eiusmod tempor incididunt ut labore et dolore magnaH" .
            "aliqua. Ut enim ad minim veniam, quis nostrud exercitationH" .
            "ullamco laboris nisi ut aliquip ex ea commodo consequat.H" .
            "Duis aute irure dolor in reprehenderit in voluptate velitH" .
            "esse cillum dolore eu fugiat nulla pariatur. Excepteur sintH" .
            "occaecat cupidatat non proident, sunt in culpa qui officiaH" .
            "deserunt mollit anim id est laborum";

        self::assertEquals($expected, $result);
    }

    public function testItCanStripAnsiEscapeSequence() : void
    {
        $result = StringUtil::stripAnsiEscapeSequence("\x1b[7mfoo\x1b[0m");
        $this->assertEquals('foo', $result);

        $result = StringUtil::stripAnsiEscapeSequence("foobar\x1b[00m\x1b[01;31m");
        $this->assertEquals('foobar', $result);

        $result = StringUtil::stripAnsiEscapeSequence("foo\x1b[00mbar\x1b[01;31mbaz\x1b[00m!!!\x1b[01;31m");
        $this->assertEquals('foobarbaz!!!', $result);
    }

    public function testSplitItemBug() : void
    {
        $test = 'Item three I guess it isn\'t that bad, is it ?';
        
        self::assertEquals(
            "Item three\nI guess it\nisn't that\nbad, is it\n?",
            StringUtil::wordwrap($test, 11)
        );
    }

    public function testLengthIgnoresAnsiEscapeSequences() : void
    {
        $result = StringUtil::length("\x1b[7mfoo\x1b[0m");
        $this->assertEquals(3, $result);

        $result = StringUtil::length("foobar\x1b[00m\x1b[01;31m");
        $this->assertEquals(6, $result);

        $result = StringUtil::length("foo\x1b[00mbar\x1b[01;31mbaz\x1b[00m!!!\x1b[01;31m");
        $this->assertEquals(12, $result);
    }

    public function testLengthIncludingAnsiEscapeSequences() : void
    {
        $result = StringUtil::length("\x1b[7mfoo\x1b[0m", false);
        $this->assertEquals(11, $result);

        $result = StringUtil::length("foobar\x1b[00m\x1b[01;31m", false);
        $this->assertEquals(19, $result);

        $result = StringUtil::length("foo\x1b[00mbar\x1b[01;31mbaz\x1b[00m!!!\x1b[01;31m", false);
        $this->assertEquals(38, $result);
    }
}
