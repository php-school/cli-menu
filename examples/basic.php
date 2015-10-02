<?php

use MikeyMike\CliMenu\CliMenu;
use MikeyMike\CliMenu\MenuItem\MenuItem;

require_once(__DIR__ . '/../vendor/autoload.php');

$menu = new CliMenu(
    'Basic CLI Menu',
    [
        new MenuItem('First Item'),
        new MenuItem('Second Item'),
        new MenuItem('Third Item')
    ],
    function (CliMenu $menu) {
        echo sprintf(
            "\n%s\n",
            $menu->getSelectedItem()->getText()
        );
    }
);

$menu->display();
