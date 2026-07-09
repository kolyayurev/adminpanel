# Widgets

Виджет — единица данных/визуализации на [CustomPage](custom-pages.md) (страница **самой
админки** — дашборд, отчёт и т.п., не публичная страница сайта). Виджет описывает себя
(слаг, заголовок, тип) и умеет отдавать данные под текущий `Request` — конструируется PHP-
классом, без обращения к blade напрямую, по тому же принципу, что и form-field'ы/блоки.

Виджет адресуется **собственным слагом** независимо от того, на какой странице (и на скольких
страницах) он показан — слаги виджетов должны быть уникальны в рамках всего приложения (это
общий реестр, как у `PageType`/`DataType`).

## Базовый класс

`KY\AdminPanel\Widgets\BaseWidget` — абстрактный класс, реализующий `WidgetContract`:

```php
namespace App\AdminPanel\Widgets;

use Illuminate\Http\Request;
use KY\AdminPanel\Widgets\BaseWidget;

class TopPagesWidget extends BaseWidget
{
    protected string $title = 'Популярные страницы';

    public function data(Request $request): array
    {
        return [
            // произвольная структура — виджет сам решает, что отдавать
        ];
    }
}
```

- `getSlug()` — идентификатор виджета (по умолчанию — snake_case имени класса без суффикса
  `Widget`; переопределяется через `::make('custom_slug')`, как у form-field'ов).
- `getTitle()` — заголовок (fluent `title()`), по умолчанию равен слагу.
- `getType()` — тип виджета, определяет какой blade/vue-компонент его рендерит
  (`adminpanel::widgets.{type}.index`); для `BaseWidget` по умолчанию тоже выводится из имени
  класса, но конкретные виды виджетов (например `ChartWidget`) фиксируют его сами.
- `data(Request $request): array` — **абстрактный**, переопределяется в конкретном виджете.
  Получает текущий `Request` целиком — можно читать любые query-параметры (фильтры по дате
  и т.п.), виджет сам решает, что с ними делать.

## Регистрация

Виджет попадает в общий реестр (`AdminPanel::getWidget($slug)`, используется async-
эндпоинтом) одним из двух способов:

1. **Автоматически** — если он присутствует в `widgets()` зарегистрированной
   [CustomPage](custom-pages.md): `AdminPanel::addCustomPage(...)` сама регистрирует все
   виджеты этой страницы, отдельно вызывать ничего не нужно.
2. **Самостоятельно**, без привязки к какой-либо странице — если хотите использовать виджет
   вне контекста `CustomPage` (встроить его данные куда угодно ещё):

   ```php
   use AdminPanel;
   use App\AdminPanel\Widgets\TopPagesWidget;

   AdminPanel::addWidget(TopPagesWidget::class);
   ```

## Графики: ChartWidget (Chart.js)

В пакет встроен `KY\AdminPanel\Widgets\ChartWidget` — базовый класс виджета-графика на
[Chart.js](https://www.chartjs.org/):

```php
namespace App\AdminPanel\Widgets;

use App\Models\Post;
use Illuminate\Http\Request;
use KY\AdminPanel\Widgets\ChartWidget;

class PostsPerDayWidget extends ChartWidget
{
    protected string $title = 'Посты по дням';

    // Опции Chart.js — любые, как в документации библиотеки (scales, plugins, legend,
    // animation и т.д.), пакет их не интерпретирует, просто передаёт во фронтенд.
    protected array $options = [
        'plugins' => ['legend' => ['display' => false]],
        'scales' => ['y' => ['beginAtZero' => true]],
    ];

    public function data(Request $request): array
    {
        $from = $request->date('from') ?? now()->subDays(30);
        $to = $request->date('to') ?? now();

        $rows = Post::query()
            ->selectRaw('date(created_at) as d, count(*) as c')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('d')->orderBy('d')->get();

        return $this->chartConfig(
            $rows->pluck('d')->all(),
            [['label' => 'Посты', 'data' => $rows->pluck('c')->all()]],
        );
    }
}
```

- `chartType(string $type)` / `getChartType()` — тип графика: `line` (по умолчанию), `bar`,
  `pie`, `doughnut` и другие типы Chart.js. **Не путать** с `getType()` из `BaseWidget` — тот
  фиксирован как `chart` у любого `ChartWidget` и определяет компонент-рендерер, а
  `chartType()` — это конкретный вид графика внутри него.
- `options(array $options)` / `getOptions()` — **универсальный доступ ко всем возможностям
  Chart.js**: любой ключ из [объекта `options` Chart.js](https://www.chartjs.org/docs/latest/general/options.html)
  (`scales`, `plugins`, `animation`, `interaction`, `responsive`, `aspectRatio` и т.д.) —
  пакет ничего не валидирует и не ограничивает, массив уходит во фронтенд как есть. Можно
  задать через свойство `protected array $options` (как в примере) или вызвать `->options([...])`
  у экземпляра — повторные вызовы мёржатся рекурсивно, а не затирают друг друга.
- `chartConfig(array $labels, array $datasets, array $options = []): array` — protected-
  хелпер, который собирает итоговый payload (`type`/`labels`/`datasets`/`options`, где
  `$options` мёржится поверх `getOptions()`) — удобен, если нужно переопределить опции just
  для конкретного запроса (например разные цвета в зависимости от диапазона дат), не трогая
  свойство `$options` по умолчанию.
- `data()` не обязан использовать `chartConfig()` — можно вернуть массив
  `['type' => ..., 'labels' => [...], 'datasets' => [...], 'options' => [...]]` вручную,
  `chartConfig()` — просто более короткий путь.

На фронте это рендерит Vue-компонент `v-chart-widget` (`resources/js/vue/ChartWidget.vue`),
зарегистрированный глобально. При монтировании он сам обращается к async-эндпоинту виджета
(см. ниже) и рисует canvas — никакого blade/JS от потребителя пакета не требуется.

## Асинхронный эндпоинт данных

```
GET /admin/widgets/{widget}/data
```

Универсальный эндпоинт — прямой аналог `dataType.table` у `DataType`: резолвит виджет по
слагу из общего реестра и возвращает `$widget->data($request)` как JSON. Не привязан ни к
какой `CustomPage` — виджет можно дёрнуть по этому URL независимо от того, где и сколько раз
он показан на страницах.

## Асинхронное обновление (фильтры)

Компонент `v-chart-widget` слушает глобальное событие `window`:

```js
window.dispatchEvent(new CustomEvent('adminpanel:widgets:refresh', {
    detail: { from: '2026-06-01', to: '2026-07-01' },
}))
```

По этому событию виджет перезапрашивает данные с эндпоинтом `adminpanel.widgets.data`,
передавая `detail` как query-параметры — так фильтр-бар страницы (свой для конкретной
`CustomPage`, пакетом не навязывается) может обновить сразу все графики на странице без
перезагрузки. Виджету на PHP-стороне для этого ничего дополнительно делать не нужно — он и
так читает параметры из `Request` в `data()`.
