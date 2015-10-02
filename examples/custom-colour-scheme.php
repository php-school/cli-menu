<?php

use MikeyMike\CliMenu\CliMenu;
use MikeyMike\CliMenu\MenuItem\MenuItem;

require_once(__DIR__ . '/../vendor/autoload.php');

ini_set('display_errors', 1);

$menu = new CliMenu(
    'Basic CLI Menu',
    [
      new MenuItem('First Item'),
      new MenuItem('Second Item'),
      new MenuItem('Third Item'),
    ],
    function (MenuItem $item, CliMenu $menu) {
        $menu->close();
        echo sprintf('You selected %s', $item->getText($menu->getStyle()));
    },
    [],
    null,
    new \MikeyMike\CliMenu\MenuStyle(null, 'red')
);

$menu->display();
