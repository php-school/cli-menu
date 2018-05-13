<?php

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\Input\Text;
use PhpSchool\CliMenu\Input\InputIO;
use PhpSchool\CliMenu\MenuStyle;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    $style = (new MenuStyle())
        ->setBg('yellow')
        ->setFg('black');
    
    $input = new class (new InputIO($menu, $menu->getTerminal()), $style) extends Text {
        public function validate(string $value) : bool
        {
            //some validation
            return true;
        }
    };

    $result = $input->ask();

    var_dump($result->fetch());
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('Enter password', $itemCallable)
    ->addLineBreak('-')
    ->setMarginAuto()
    ->build();

$menu->open();