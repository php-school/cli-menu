<?php

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu Custom Item Extra')
    ->addItem('First Item', $itemCallable, true)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable, true)
    ->setItemExtra('[COMPLETE!]')
    ->addLineBreak('-')
    ->build();

$menu->open();
