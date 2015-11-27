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

#### Quick Setup
Here is a super basic exmple menu which will echo out the text of the selected item to get you started.
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


#### Examples

Check out the [examples](examples) directory and run them to check out what is possible! 

##### Basic Menu 
<img width="600" alt="basic" src="https://cloud.githubusercontent.com/assets/2817002/11442386/cb0e41a2-950c-11e5-8dd6-913aeab1632a.png">

##### Basic Menu with separation
<img width="600" alt="basic-seperation" src="https://cloud.githubusercontent.com/assets/2817002/11442388/cdece950-950c-11e5-8128-4f849a1aea9f.png">

##### Menu with crazy separation
<img width="600" alt="crazy-seperation" src="https://cloud.githubusercontent.com/assets/2817002/11442389/d04627fc-950c-11e5-8c80-f82b8fe3f5da.png">

##### Custom Styles
<img width="600" alt="custom-styles" src="https://cloud.githubusercontent.com/assets/2817002/11442391/d3d72d1c-950c-11e5-9698-c2aeec002b24.png">

##### Useful Separation
<img width="600" alt="useful-seperation" src="https://cloud.githubusercontent.com/assets/2817002/11442393/d862c72e-950c-11e5-8cbc-d8c73899627a.png">

##### Item Extra
<img width="600" alt="item-extra" src="https://cloud.githubusercontent.com/assets/2817002/11442395/dfe460f2-950c-11e5-9aed-9bc9c91b7ea6.png">

##### Remove Defaults
<img width="600" alt="remove-defaults" src="https://cloud.githubusercontent.com/assets/2817002/11442399/e3e8b8a6-950c-11e5-8dad-fdd4db93b850.png">

##### Submenu
<img width="600" alt="submenu" src="https://cloud.githubusercontent.com/assets/2817002/11442401/e6f03ef2-950c-11e5-897a-6d55496a4105.png">
<img width="600" alt="submenu-options" src="https://cloud.githubusercontent.com/assets/2817002/11442403/eaf4782e-950c-11e5-82c5-ab57f84cd6bc.png">


### API

The `CliMenu` object is constructed via the Builder class

```php
$menu = (new CliMenuBuilder)
    /**
     *  Customise
    **/
    ->build();
```

Once you have a menu object, you can open and close it like so:

```php
$menu->open();
$menu->close();
```

#### Appearance

You can change the foreground and background colour of the menu to any of the following colors:

* black
* red
* green
* yellow
* blue
* magenta
* cyan
* white
* 

```php
$menu = (new CliMenuBuilder)
    ->setForegroundColour('green')
    ->setBackgroundColour('black')
    ->build();
```

The width, padding and margin can also be customised:

```php
$menu = (new CliMenuBuilder)
    ->setWidth(200)
    ->setPadding(10)
    ->setMargin(5)
    ->build();
```

The marker displayed by the side of the currently active item can be modified, UTF-8 characters are supported:

```php
$menu = (new CliMenuBuilder)
    ->setSelectedMarker('>')
    ->build();
```

The un-selected marker can also be modified, for example, you may want to disable it:

```php
$menu = (new CliMenuBuilder)
    ->setUnselectedMarker(' ')
    ->build();
```

Once you get going you might just end up with something that looks a little like this... 

![Learn You PHP CLI Menu](https://cloud.githubusercontent.com/assets/2174476/11409864/be082444-93ba-11e5-84ab-1b6cfa38aef8.png)

### Tests

We're amazing developers who don't need tests...

Joking aside, we're big advocates of testing so these are in the pipeline, bear with us, we just did this a bit backwards :) 
