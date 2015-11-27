<p align="center">
    <img src="https://cloud.githubusercontent.com/assets/2174476/10601666/071e3e24-770b-11e5-9cba-8ae6402ff550.gif" width="300" />
</p>

<p align="center">
    <a href="https://travis-ci.org/php-school/cli-menu" title="Build Status" target="_blank">
     <img src="https://img.shields.io/travis/php-school/cli-menu.svg?style=flat-square&label=Linux" />
    </a>
    <a href="https://ci.appveyor.com/project/mikeymike/cli-menu" title="Windows Build Status" target="_blank">
     <img src="https://img.shields.io/appveyor/ci/mikeymike/cli-menu/master.svg?style=flat-square&label=Windows" />
    </a>
    <a href="https://codecov.io/github/php-school/cli-menu" title="Coverage Status" target="_blank">
     <img src="https://img.shields.io/codecov/c/github/php-school/cli-menu.svg?style=flat-square" />
    </a>
    <a href="https://scrutinizer-ci.com/g/php-school/cli-menu/" title="Scrutinizer Code Quality" target="_blank">
     <img src="https://img.shields.io/scrutinizer/g/php-school/cli-menu.svg?style=flat-square" />
    </a>
</p>

---

### Installation

```bash
composer require php-school/cli-menu
```

### Usage

The PhpSchool elephpants are working hard on delivering a top notch learning experience on the command line. 
While we do that documentation is being neglected so please take a look at the examples to find out how to use this. 

As a quick example here is a super basic menu which will echo out the text of the selected item.
```php
<?php

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable)
    ->addLineBreak('-')
    ->build();

$menu->open();
```

Once you get going you might just end up with something that looks a little like this... 

![Learn You PHP CLI Menu](https://cloud.githubusercontent.com/assets/2174476/11409864/be082444-93ba-11e5-84ab-1b6cfa38aef8.png)

### Tests

We're amazing developers who don't need tests...

Joking aside, we're big advocates of testing so these are in the pipeline, bear with us, we just did this a bit backwards :) 
