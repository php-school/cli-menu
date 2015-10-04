<?php

use MikeyMike\CliMenu\CliMenu;
use MikeyMike\CliMenu\MenuItem\MenuItem;

require_once(__DIR__ . '/../vendor/autoload.php');

ini_set('display_errors', 1);

$menu = new CliMenu(
    'Basic CLI Menu',
    [
      new MenuItem('First Item'),
      new MenuItem('Second Item, but we have a really long menu item that could cause some problems here?'),
      new MenuItem('Third Item, this could be long too, maybe just not as long :)'),
    ],
    function (MenuItem $item, CliMenu $menu) {
        $menu->close();
        echo sprintf('You selected %s', $item->getText($menu->getStyle()));
    },
    [],
    null,
    new \MikeyMike\CliMenu\MenuStyle('black', 'red', 50, 4, 4, '=> ')
);

$menu->display();
