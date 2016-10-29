<?php

namespace PhpSchool\CliMenuTest\Util;

use PhpSchool\CliMenu\Util\StringUtil;
use PHPUnit_Framework_TestCase;

/**
 * Class StringUtilTest
 * @package PhpSchool\CliMenuTest\MenuItem
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class StringUtilTest extends PHPUnit_Framework_TestCase
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
