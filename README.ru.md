## Laravel Grouped Widgets

## Установка
Подключаем пакет через composer:
```
composer require "vanchelo/laravel-grouped-widgets"
```

В конфигурационный файл `config/app.php` в секцию `providers` добавить строку:
```
'Vanchelo\GroupedWidgets\WidgetsServiceProvider',
```
и в секцию `aliases` след. строку, для комфортной работы с библиотекой:
```
'Widget' => 'Vanchelo\GroupedWidgets\Facades\Widget',
```

## Использование

### Создание виджета
Для создание нового виджета выполните след. код в консоли приложения:
```
php artisan make:widget Block 
```
После выполнения команды заготовка для виджета будет создана в папке `app/Widgets/` с именем `Block.php`.

#### Регистрация виджета
В качестве имени виджета старайтесь не использовать `.-`, а также другие символы которые запрещены использовать в именах методов PHP, но придерживайтесь этого правила только в том случае, если хотите обращаться к виджетам в такой манере `Widget::block()`. Доступны и другие способы доступа к виджету, о них расскажу чуть ниже.
 
Простая регистрация виджета:
```
Widget::register('block', 'App\Widgets\Block');
```
Альтернативный вариант:
```
widget()->register('block', 'App\Widgets\Block');
```
С указанием группы виджета и положения:
```
Widget::register('block', 'App\Widgets\Block')->group('left')->order(99);
```
Регистрация виджета только, если виджет с таким именем не был зарегистрирован ранее:
```
Widget::registerIf('block', 'App\Widgets\Block')->group('left')->order(99);
```
В качестве второго аргумента метода `register`и `registerIf` может быть замыкание или экземпляр класса с реализованным методом `__invoke`:

```
// замыкание
Widget::register('block', function ()
{
	return '<div>Block</div>';
});

// Экземпляр класса
Widget::register('block', new App\Widgets\Block);
```

По умолчанию все виджеты имею группу `default`.

Для вывода всей группы в шаблоне используйте след. код:
```
{!! Widget::group('default') !!}
```
или
```
{!! widgets('default') !!}
```
или
```
{!! widget()->group('default') !!}
```
Так же вторым параметром метода `group` можно передать разделитель при выводе виджетов группы, например:
```
{!! Widget::group('left', '<hr>') !!}
```
Выбирайте понравившейся вам варинат.

Если метод `group` выводил отрендеренный результат, то для того чтобы получить коллекцию виджетов определенной группы есть след. метод:
```
/** @var Collection $widgets */
$widgets = Widget::getGroup('left');
```

Метод для получения коллекции всех зарегистрированных виджетов:
```
$widgets = Widget::getCollection();
```

Метод для проверки наличия виджета в коллекции по имени:
```
Widget::has('menu');
```

Пример виджета

```
<?php namespace App\Widgets;

use Vanchelo\Widgets\AbstractWidget;
use Illuminate\Contracts\View\Factory as ViewFactory;

class Block extends AbstractWidget
{
	/**
	 * @var ViewFactory
	 */
	private $view;

	function __construct(ViewFactory $view)
	{
		$this->view = $view;
	}

	public function render($title = 'Меню', $view = 'widgets.topmenu')
	{
		return $this->view->make($view, compact('title'))->render();
	}
}
```

В конструкторе `__construct()` вы можете указать необходимые зависимости для работы вашего виджета. DI Laravel автоматически выполнит их инъекцию.

В методе `render` виджета вы можете указать необходимые аргументы для управления выводом.

Например:

```
{!! Widget::block() !!}
{!! Widget::block('Верхнее меню') !!}
{!! Widget::block('Какой-то заголовок', 'another-view') !!}
```

Еще один варинат получение виджета с передачей в него массива параметров:
```
{!! Widget::make('block', ['Главное меню', 'menu.top']) !!}
```

### Методы

- Widget::register
- Widget::registerIf
- Widget::has
- Widget::get
- Widget::getCollection
- Widget::getGroup
- Widget::group
- Widget::make
- Widget::{widget_name}

### События

При первом обращении к виджету срабатывает событие `widget.resolved: {widget_name}`, где доступен один параметр
```
['widget' => object('Vanchelo\GroupedWidgets\Widget)]
```
На этом этапе можно что-то модифицировать при необходимости.

Непосредственно при выводе виджета срабатывает событие `widget.rendering: {widget_name}`, где доступны два параметра 
```
[
	'widget' => object('Vanchelo\GroupedWidgets\Widget),
	'data' => []
]
```
`data` - массив агрументов которые были переданы в виджет при выводе. 

При выводе группы так же срабатывает событие `widget.group.rendering: {group_name}`, где так же доступны два параметра
```
[
	'group' => object('Illumintae\Support\Collection),
	'output' => []
]
```
`group` - группа с виджетами, `output` - массив отрендеренных виджетов.


Конечно же вы можете написать и самостоятельно виджет, вот такой, например:

```
<?php namespace App\Widgets;

class Block
{
	public function happy()
	{
		return view('widgets.happy')->render();
	}
	
	function __invoke()
	{
		return $this->happy();
		/* или так */
		// return call_user_func_array([$this, 'happy'], func_get_args());
	}
}
```
Метод `__invoke` на данный момент обязателен, т.к. при рендере виджета вызывается именно он. Возможно в будущем я пересмотрю это поведение.

[Здесь](https://github.com/vanchelo/Laravel-Grouped-Widgets/blob/master/src/AbstractWidget.php) можно посмотреть как реализован абстрактный виджет и написать свой по аналогии или ипользовать команду `php artisan make:widget WidgetName` для создания виджета из заготовки.
