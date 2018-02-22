<p align="center">
    <img src="https://cloud.githubusercontent.com/assets/2174476/10601666/071e3e24-770b-11e5-9cba-8ae6402ff550.gif" width="300" />
</p>

<p align="center">
    <a href="https://travis-ci.org/php-school/cli-menu" title="Build Status" target="_blank">
     <img src="https://img.shields.io/travis/php-school/cli-menu/master.svg?style=flat-square&label=Linux" />
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
    <a href="https://phpschool-team.slack.com/messages">
      <img src="https://phpschool.herokuapp.com/badge.svg">
    </a>
</p>

---
## Contents

  * [Instalação](#instalação)
  * [Modo de Usar](#modo-de-usar)
    * [Instalação Rápida](#instalação-rápida)
    * [Exemplos](#exemplos)
  * [API](#api)
  * [Traduções](#traduções)
  * [Integrações](#integrações)


### Instalação

```bash
composer require php-school/cli-menu
```

### Modo de Usar

#### Instalação rápida
Um exemplo básico bem simples de menu onde será apenas exibido o text do item selecionado, apenas para você iniciar.
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


#### Exemplos

Veja mais [exemplos](examples) na pasta de exemplos e execute-os para ver todas as possibilidades. 

##### Menu Básico
<img width="600" alt="basic" src="https://cloud.githubusercontent.com/assets/2817002/11442386/cb0e41a2-950c-11e5-8dd6-913aeab1632a.png">

##### Menu Básico com Separação
<img width="600" alt="basic-seperation" src="https://cloud.githubusercontent.com/assets/2817002/11442388/cdece950-950c-11e5-8128-4f849a1aea9f.png">

##### Menu Básico com Separação bem louca
<img width="600" alt="crazy-seperation" src="https://cloud.githubusercontent.com/assets/2817002/11442389/d04627fc-950c-11e5-8c80-f82b8fe3f5da.png">

##### Estilos Personalizados
<img width="600" alt="custom-styles" src="https://cloud.githubusercontent.com/assets/2817002/11442391/d3d72d1c-950c-11e5-9698-c2aeec002b24.png">

##### Separação Útil
<img width="600" alt="useful-seperation" src="https://cloud.githubusercontent.com/assets/2817002/11442393/d862c72e-950c-11e5-8cbc-d8c73899627a.png">

##### Itens adicionais
<img width="600" alt="item-extra" src="https://cloud.githubusercontent.com/assets/2817002/11442395/dfe460f2-950c-11e5-9aed-9bc9c91b7ea6.png">

##### Remover padrão
<img width="600" alt="remove-defaults" src="https://cloud.githubusercontent.com/assets/2817002/11442399/e3e8b8a6-950c-11e5-8dad-fdd4db93b850.png">

##### Sub-menu
<img width="600" alt="submenu" src="https://cloud.githubusercontent.com/assets/2817002/11442401/e6f03ef2-950c-11e5-897a-6d55496a4105.png">
<img width="600" alt="submenu-options" src="https://cloud.githubusercontent.com/assets/2817002/11442403/eaf4782e-950c-11e5-82c5-ab57f84cd6bc.png">

##### Desabilitar Itens & Sub-menus
<img width="600" alt="submenu" src="https://cloud.githubusercontent.com/assets/2174476/19047849/868fa8c0-899b-11e6-9004-811c8da6d435.png">

##### Mensagem instantânea
<img width="600" alt="submenu" src="https://cloud.githubusercontent.com/assets/2817002/19786090/1f07dad6-9c94-11e6-91b0-c20ab2e6e27d.png">

##### Caixa de confirmação
<img width="600" alt="submenu" src="https://cloud.githubusercontent.com/assets/2817002/19786092/215d2dc2-9c94-11e6-910d-191b7b74f4d2.png">

### API

O objeto `CliMenu` construído pela classe Builder

```php
$menu = (new CliMenuBuilder)
    /**
     *  Customise
    **/
    ->build();
```

Assim que você tem o objeto de menu, você pode abri-lo e fecha-lo da seguinte forma:

```php
$menu->open();
$menu->close();
```

#### Aparência

Você pode trocar a cor do primeiro plano e do plano de fundo do menu para qualquer uma das seguintes cores

* black (Preto)
* red (Vermelho)
* green (Verde)
* yellow (Amarelo)
* blue (Azul)
* magenta (Magenta)
* cyan (Ciano)
* white (Branco)

```php
$menu = (new CliMenuBuilder)
    ->setForegroundColour('green')
    ->setBackgroundColour('black')
    ->build();
```

A largura (width), o espaçamento (padding) e a margem (margin) também podem ser personalizadas:

```php
$menu = (new CliMenuBuilder)
    ->setWidth(200)
    ->setPadding(10)
    ->setMargin(5)
    ->build();
```

Modificar o texto do botão de sair:

```php
$menu = (new CliMenuBuilder)
    ->setExitButtonText("Don't you want me baby?")
    ->build();
```

Você pode remover o botão de sair, caso assim deseje:

```php
$menu = (new CliMenuBuilder)
    ->disableDefaultItems()
    ->build();
```

Observação: Isto também irá desabilitar o botão Voltar (Go Back) para os sub-menus.

O marcador exibido ao lado do do item ativo pode ser modificado, caracteres UTF-8 são suportados.
O marcador for itens não selecionados também pode ser modificado. Se você quiser desabilita-lo, apenas defina-o como um caractere de espaço.

```php
$menu = (new CliMenuBuilder)
    ->setUnselectedMarker('❅')
    ->setSelectedMarker('✏')
    
    //disable unselected marker
    ->setUnselectedMarker(' ')
    ->build();
```

Você pode definir um título para o seu menu e definir um separar personalizado, uma linha que será exibida abaixo do título.
Qualquer texto que você passar para `setTitleSeparator` será repetido pelo tamanho da largura do Menu.

```php
$menu = (new CliMenuBuilder)
    ->setTitle('One Menu to rule them all!')
    ->setTitleSeparator('*-')
    ->build();
```

#### Item Extra

Você pode, opcionalmente, exibir algum texto arbitrário do lado direito de item. Você pode personalizar este texto e
você pode indicar em quais itens ele será exibido. Nós usamos esta função para exibir o texto `[COMPLETED]` nos exercícios
que foram completados, onde o menu exibe a lista de exercícios para uma aplicação de workshop.

O terceiro parâmetro do método `addItem` é um boolean que define se será exibido o item extra ou não. O padrão é false. 

```php
$menu = (new CliMenuBuilder)
    ->setItemExtra('✔')
    ->addItem('Exercise 1', function (CliMenu $menu) { echo 'I am complete!'; }, true)
    ->build();
```

#### Itens

Existem alguns tipos de itens que você pode adicionar ao seu menu

* Item Selecionável - Este é o tipo de item que você precisa para qualquer coisa que for selecionável (você pode apertar enter e isso irá invocar o método invocável) 
* Item de Quebra de Linha - Este item é usado para separar áreas, ele pode abranger várias linhas e será da largura do Menu. Qualquer string passada será repetida.
* Item Estático - Este item irá exibir qualquer texto que for fornecido, muito útil para cabeçalhos.
* Item Ascii Art - Este item é um tipo especial e permite o uso de Ascii art. Ele automaticamente cuida do espaçamento e do alinhamento.
* Item de Sub Menu - Item especial para permitir um item abrir outro menu. Muito útil para quando for criar um menu de opções.

#### Item Selecionável

```php
$menu = (new CliMenuBuilder)
    ->addItem('The Item Text', function (CliMenu $menu) { 
        echo 'I am alive!'; 
    })
    ->build();
```

Você pode adicionar múltiplos itens de uma única vez, como no exemplo:

```php
$callable = function (CliMenu $menu) {
    echo 'I am alive!';
};

$menu = (new CliMenuBuilder)
    ->addItems([
        ['Item 1', $callable],
        ['Item 2', $callable],
        ['Item 3', $callable],
    ])
    ->build();
```

Observação: Você pode adicionar quantos itens você quiser e todos eles podem ter diferentes ações. A ação a ser executada
é o que for definido como segundo parâmetro e ele deve ser um tipo válido PHP de `callable`. Preferencialmente, utilize classes
do tipo `Invokable` para manter suas ações isoladas e para que possam ser facilmente testadas.

#### Item de Quebra de Linha

```php
$menu = (new CliMenuBuilder)
    ->addLineBreak('<3', 2)
    ->build();
```

O código acima irá repetir uma sequência de caracteres `<3` sobre o Menu por 2 linhas. 

#### Item Estático

Itens estáticos são similares aos de QUebra de linha, no entanto, eles não repetem e não são preenchidos. É exibido como ele é.
Se o texto for mais longo que a largura do menu, ele será exibido na linha subsequente.

```php
$menu = (new CliMenuBuilder)
    ->addStaticItem('AREA 1')
    //add some items here
    ->addStaticItem('AREA 2')
    //add some boring items here
    ->addStaticItem('AREA 51')
    //add some top secret items here 
    ->build();
```

#### Ascii Art Item

O código abaixo irá colocar a arte Ascii no centro do seu menu. Use uma das constantes abaixo para alterar o alinhamento:

* AsciiArtItem::POSITION_CENTER
* AsciiArtItem::POSITION_LEFT
* AsciiArtItem::POSITION_RIGHT

```php

$art = <<<ART
        _ __ _
       / |..| \
       \/ || \/
        |_''_|
      PHP SCHOOL
LEARNING FOR ELEPHANTS
ART;

$menu = (new CliMenuBuilder)
    ->addAsciiArt($art, AsciiArtItem::POSITION_CENTER)
    ->build();
```    

#### Item de Sub Menu

Sub-menus são muito poderosos. Você pode adicionar Menus aos Menus, MASOQUÊ?? Você pode ter um seu menu principal e também opções para o seu menu.
As itens de opções serão iguais itens normais de menu, excepto que quando você pressiona-lo, irá entrar em outro menu, no qual
qual pode ter estilos e cores diferenciados.

```php

$callable = function (CliMenu $menu) {
    echo "I'm just a boring selectable item";
};

$menu = (new CliMenuBuilder)
    ->addItem('Normal Item', $callable)
    ->addSubMenu('Super Sub Menu')
        ->setTitle('Behold the awesomeness')
        ->addItem(/** **/)
        ->end()
    ->build();
```

Neste exemplo, um único sub-menu será criado. Assim que entrarmos no sub-menu, você será capaz de retornar ao menu principal.
ou então sair completamente do menu. Um botão Go Back (Voltar) será automaticamente adicionado, e você pode personalizar o texto deles dessa forma:

```php
->addSubMenu('Super Sub Menu')
    ->setTitle('Behold the awesomeness')
    ->setGoBackButtonText('Descend to chaos')
```    

Existem algumas coisas para se prestar atenção na sintaxe e no processo de criação aqui

1. `addSubMenu` retorna uma instância de `CliMenuBuilder` assim você pode personalizar do jeito que precisar no objeto pai
2. Se você não modificar os estilos do sub-menu (ex, cores) ele irá herdar os estilos do menu pai
3. Você pode chamar o método `end()` na instância do sub-menu `CliMenuBuilder` para recuperar a instância pai `CliMenuBuilder` novamente. Isto é bem útil quando for fazer encadeamentos.

Se você precisar da instância `CliMenu` do Sub Menu você pode recupera-lo após o menu principal ser construído.

```php
$mainMenuBuilder = new CliMenuBuilder;
$subMenuBuilder = $mainMenuBuilder->addSubMenu('Super Sub Menu');

$menu = $mainMenuBuilder->build();
$subMenu = $mainMenuBuilder->getSubMenu('Super Sub Menu');
```

Você apenas pode fazer isso após o menu principal ter sido construído. Isto acontece porque o builder do menu principal toma conta de criar todos os sub menus.

#### Desabilitando Itens & Sub Menus

Neste exemplo nós estamos desabilitando alguns itens e um sub-menu, porém ainda iremos exibi-los na saída.

```php
$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu Disabled Items')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable, false, true)
    ->addItem('Third Item', $itemCallable, false, true)
    ->addSubMenu('Submenu')
        ->setTitle('Basic CLI Menu Disabled Items > Submenu')
        ->addItem('You can go in here!', $itemCallable)
        ->end()
    ->addSubMenu('Disabled Submenu')
        ->setTitle('Basic CLI Menu Disabled Items > Disabled Submenu')
        ->addItem('Nope can\'t see this!', $itemCallable)
        ->disableMenu()
        ->end()
    ->addLineBreak('-')
    ->build();
```

O terceiro parâmetro do método `->addItem` é o que realmente desabilita o item enquanto o `->disableMenu()` desabilita o menu relevante. 

O resultado é um menu completo com algumas linhas escurecidas para denotar que elas estão desabilitadas. Quando um usuário navega,
estes itens são "pulados" e o menu o joga para o próximo menu selecionável.

#### Re-desenhando o menu

Você pode modificar o menu e os seus estilos quando estiver executando uma ação e então você pode redesenha-los. Neste exemplo nós trocaremos
a cor de fundo dentro de uma ação.


```php
$itemCallable = function (CliMenu $menu) {
    $menu->getStyle()->setBg($menu->getStyle()->getBg() === 'red' ? 'blue' : 'red');
    $menu->redraw();
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

#### Recuperando, Removendo e Adicionando Itens

Você também pode interagir com os itens do menu dentro de uma ação:

```php
use PhpSchool\CliMenu\MenuItem\LineBreakItem;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    foreach ($menu->getItems() as $item) {
        $menu->removeItem($item);
    }
    
    $menu->addItem(new LineBreakItem('-'));

    $menu->redraw();
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

#### Diálogos (Caixas de Mensagem)

##### Instântaneo (Flash)

Exibe uma mensagem de uma linha sobre o menu. Ele possui um objeto de estilização separado e que possui uma cor
diferenciada do menu principal por padrão. Pode ser modificado para se encaixar no seu próprio estilo. A caixa de mensagem
é fechada assim que qualquer tecla for pressionada. No exemplo abaixo nós trocamos a cor de fundo para verde.


```php
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    $flash = $menu->flash("PHP School FTW!!");
    $flash->getStyle()->setBg('green');
    $flash->display();
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

##### Confirmação

Caixas de seleção são muito similares do tipo flash, excepto que neste tipo é mostrado um botão que deve ser selecionado
para que a caixa desapareça. O texto do botão também pode ser customizado.

```php
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    $menu->confirm('PHP School FTW!')
        ->display('OK!');
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

---

Conforme você for brincando com nossa aplicação você irá fazer coisas semelhantes a isto...

![Learn You PHP CLI Menu](https://cloud.githubusercontent.com/assets/2174476/11409864/be082444-93ba-11e5-84ab-1b6cfa38aef8.png)

Você pode ver o código de construção neste link para mais clareza e em como fazer configurações mais avançadas:
[PHP School](https://github.com/php-school/php-workshop/blob/3240d3217bbf62b1063613fc13eb5adff2299bbe/src/Factory/MenuFactory.php)

### Traduções 
_(As traduções podem não estar atualizadas haja visto que são feitas pela comunidade)_
Ver este documento em [Inglês (en_US)](https://github.com/php-school/cli-menu/blob/master/README.md) 


### Integrações

 * [Symfony Console](https://github.com/RedAntNL/console)
