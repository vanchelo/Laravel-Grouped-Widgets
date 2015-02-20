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
Выбирайте понравившейся вам варинат.

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
	}
}
```
Метод `__invoke` на данный момент обязателен, т.к. при рендере виджета вызывается именно он. Возможно в будущем я пересмотрю это поведение.

[Здесь](https://github.com/vanchelo/Laravel-Grouped-Widgets/blob/master/src/AbstractWidget.php) можно посмотреть как реализован абстрактный виджет и написать свой по аналогии или ипользовать команду `php artisan make:widget WidgetName` для создания виджета из заготовки.