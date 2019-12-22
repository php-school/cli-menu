<?php

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('CLI Menu')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addLineBreak('-')
    ->addSubMenu('Options', function (CliMenuBuilder $b) {
        $b->setTitle('CLI Menu > Options')
            ->addItem('First option', function (CliMenu $menu) {
                echo sprintf('Executing option: %s', $menu->getSelectedItem()->getText());
            })
            ->addLineBreak('-');
    })
    ->setWidth(70)
    ->setMarginAuto()
    ->setBackgroundColour('yellow')
    ->build();

$menu->open();
