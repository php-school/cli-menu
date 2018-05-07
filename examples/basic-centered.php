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
    ->addItem('Make menu wider', function (CliMenu $menu) {
        $menu->getStyle()->setWidth($menu->getStyle()->getWidth() + 10);
        $menu->redraw();
    })
    ->addItem('Third Item', $itemCallable)
    ->addLineBreak('-')
    ->setWidth(70)
    ->setMarginAuto()
    ->build();

$menu->open();
