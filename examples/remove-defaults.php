<?php

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu Remove Defaults')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable)
    ->disableDefaultItems()
    ->addItem('CUSTOM CLOSE', function (CliMenu $menu) {
        $menu->close();
    })
    ->build();

$menu->open();

echo 'Execution will just continue after calling $menu->close()';

sleep(2);

echo "\n\nYou can pretty much do anything you want then, maybe even re-open it?";

sleep(2);

$menu->open();
