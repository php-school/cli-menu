<?php

use MikeyMike\CliMenu\CliMenu;
use MikeyMike\CliMenu\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('First Item')
    ->addItem('Second Item')
    ->addItem('Third Item')
    ->addItemCallable($itemCallable)
    ->disableDefaultActions()
    ->addAction('CLOSE', function (CliMenu $menu) {
        $menu->close();
    })
    ->build();

$menu->display();

echo 'Execution will just continue after calling $menu->close()';

sleep(2);

echo "\n\nYou can pretty much do anything you want then, maybe even re-open it?";

sleep(2);

$menu->open();
