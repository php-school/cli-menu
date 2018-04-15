<?php

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    $result = $menu->askNumber()
        ->setPlaceholderText(10)
        ->ask();

    var_dump($result->fetch());
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('Enter number', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable)
    ->addLineBreak('-')
    ->build();

$menu->open();
