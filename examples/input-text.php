<?php
declare(strict_types=1);

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    $result = $menu->askText()
        ->setPlaceholderText('Enter something here')
        ->ask();

    var_dump($result->fetch());
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('Enter text', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable)
    ->addLineBreak('-')
    ->setMarginAuto('-')
    ->build();

$menu->open();
