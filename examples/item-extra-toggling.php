<?php

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    if ($menu->getSelectedItem()->showsItemExtra()) {
        $menu->getSelectedItem()->hideItemExtra();
    } else {
        $menu->getSelectedItem()->showItemExtra();
    }
    $menu->redraw();
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu Custom Item Extra')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable)
    ->setItemExtra('[COMPLETE!]')
    ->displayExtra()
    ->addLineBreak('-')
    ->build();

$menu->open();
