<?php

namespace PhpSchool\CliMenuTest\Action;

use PhpSchool\CliMenu\Action\ExitAction;
use PhpSchool\CliMenu\CliMenu;
use PHPUnit_Framework_TestCase;

/**
 * Class ExitActionTest
 * @package PhpSchool\CliMenuTest
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class ExitActionTest extends PHPUnit_Framework_TestCase
{
    public function testExitActionClosesMenu()
    {
        $menu = $this->getMockBuilder(CliMenu::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $action = new ExitAction;
        
        $menu
            ->expects($this->once())
            ->method('close');
        
        $action->__invoke($menu);
    }
}
