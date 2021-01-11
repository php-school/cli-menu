<?php

namespace PhpSchool\CliMenuTest\Action;

use PhpSchool\CliMenu\Action\ExitAction;
use PhpSchool\CliMenu\CliMenu;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class ExitActionTest extends TestCase
{
    public function testExitActionClosesMenu() : void
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
