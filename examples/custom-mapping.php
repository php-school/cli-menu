<?php

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$exit = function (CliMenu $menu) {
    $menu->close();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('First Item', $exit)
    ->addItem('Second Item', $exit)
    ->addLineBreak('-')
    ->build();

$menu->addCustomControlMapping('x', $exit);
$menu->open();
