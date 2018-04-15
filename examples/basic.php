<?php

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', function (CliMenu $menu) {
        $menu->getStyle()->setBg('red');
        $menu->redraw();
    })
    ->addItem('Third Item', function (CliMenu $menu) {
        $menu->getStyle()->setBg('default');
        $menu->redraw();
    })
    ->addLineBreak('-')
    ->build();

$menu->open();
