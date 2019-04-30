<?php

use PhpSchool\CliMenu\Builder\SplitItemBuilder;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->enableAutoShortcuts()
    ->setTitle('Basic CLI Menu')
    ->addItem('[F]irst Item', $itemCallable)
    ->addItem('Se[c]ond Item', $itemCallable)
    ->addItem('Third [I]tem', $itemCallable)
    ->addSubMenu('[O]ptions', function (CliMenuBuilder $b) {
        $b->setTitle('CLI Menu > Options')
            ->addItem('[F]irst option', function (CliMenu $menu) {
                echo sprintf('Executing option: %s', $menu->getSelectedItem()->getText());
            })
            ->addLineBreak('-');
    })
    ->addSplitItem(function (SplitItemBuilder $b) use ($itemCallable) {
        $b->addItem('Split Item [1]', function() { echo 'Split Item 1!'; })
            ->addItem('Split Item [2]', function() { echo 'Split Item 2!'; })
            ->addItem('Split Item [3]', function() { echo 'Split Item 3!'; })
            ->addSubMenu('Split Item [4]', function (CliMenuBuilder $builder) use ($itemCallable) {
                $builder->addItem('Third [I]tem', $itemCallable);

            });
    })
    ->addLineBreak('-')
    ->build();

$menu->open();

