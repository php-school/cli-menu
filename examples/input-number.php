<?php
declare(strict_types=1);

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    $number = $menu->askNumber();
    $number->getStyle()
        ->setBg('180')
        ->setFg('245');
    
    $result = $number->setPlaceholderText(10)
        ->ask();

    var_dump($result->fetch());
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('Enter number', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable)
    ->setBackgroundColour('237')
    ->setForegroundColour('156')
    ->setBorder(0, 0, 0, 2, '165')
    ->setPaddingTopBottom(4)
    ->setPaddingLeftRight(8)
    ->addLineBreak('-')
    ->setMarginAuto()
    ->build();

$menu->open();
