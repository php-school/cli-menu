<?php

namespace PhpSchool\CliMenuTest\Action;

use PhpSchool\CliMenu\Action\GoBackAction;
use PhpSchool\CliMenu\CliMenu;
use PHPUnit_Framework_TestCase;

/**
 * Class GoBackActionTest
 * @package PhpSchool\CliMenuTest
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class GoBackActionTest extends PHPUnit_Framework_TestCase
{
    public function testGoBackActionClosesMenuAndOpensParentIfMenuHasAParent()
    {
        $parent = $this->getMockBuilder(CliMenu::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $menu = $this->getMockBuilder(CliMenu::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $action = new GoBackAction;
        
        $menu
            ->expects($this->once())
            ->method('getParent')
            ->will($this->returnValue($parent));
        
        $menu
            ->expects($this->once())
            ->method('close');
        
        $parent
            ->expects($this->once())
            ->method('open');
        
        $action->__invoke($menu);
    }
}
