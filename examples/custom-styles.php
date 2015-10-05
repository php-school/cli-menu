<?php

use MikeyMike\CliMenu\CliMenu;
use MikeyMike\CliMenu\CliMenuBuilder;
use MikeyMike\CliMenu\MenuItem\MenuItem;use MikeyMike\CliMenu\MenuStyle;

require_once(__DIR__ . '/../vendor/autoload.php');

$menu = (new CliMenuBuilder('Custom Styled CLI Menu'))
    ->addItem(new MenuItem('First Item'))
    ->addItem(new MenuItem('Second Item, but we have a really long menu item that could cause some problems here?'))
    ->addItem(new MenuItem('Third Item, this could be long too, maybe just not as long :)'))
    ->setItemCallback(function (CliMenu $menu) {
        echo sprintf('You selected "%s"', $menu->getSelectedItem()->getText());
    })
    ->setMenuStyle(new MenuStyle('black', 'green', 50, 4, 4, '=> '))
    ->build();

$menu->display();
