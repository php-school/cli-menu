<?php
declare(strict_types=1);

namespace PhpSchool\CliMenuTest\Action;

use PhpSchool\CliMenu\Action\GoBackAction;
use PhpSchool\CliMenu\CliMenu;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class GoBackActionTest extends TestCase
{
    public function testGoBackActionClosesMenuAndOpensParentIfMenuHasAParent() : void
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
            ->method('closeThis');
        
        $parent
            ->expects($this->once())
            ->method('open');
        
        $action->__invoke($menu);
    }
}
