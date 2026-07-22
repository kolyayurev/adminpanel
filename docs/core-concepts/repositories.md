# Repositories

Репозиторий — это прослойка между [DataType](datatype.md) и Eloquent-моделью. Он отвечает за
то, **какая модель** стоит за DataType, как создаются записи и **какой запрос** питает
[серверную таблицу](datatables.md) (включая фильтрацию).

Базовый класс — `KY\AdminPanel\Repositories\BaseRepository`.

## Создание и подключение

```bash
php artisan adminpanel:make:repository Post Post
```

Первый параметр — имя репозитория, второй — имя модели из `App\Models`. Подробнее —
[команды](../getting-started/commands.md).

```php
namespace App\AdminPanel\Repositories;

use App\Models\Post;
use Illuminate\Http\Request;
use KY\AdminPanel\Contracts\DataTypeContract;
use KY\AdminPanel\Repositories\BaseRepository;

class PostRepository extends BaseRepository
{
    public function modelClass(): string
    {
        return Post::class;
    }

    // Запрос для DataTables: здесь же — фильтрация/скоупы/eager-load
    public function getDataTableFilter(Request $request, DataTypeContract $dataType)
    {
        return $this->model->query();
    }
}
```

Репозиторий подключается в DataType (обычно в конструкторе):

```php
public function __construct()
{
    $this->repository = new PostRepository();
}
```

## Методы BaseRepository

- `modelClass(): string` — FQCN модели (обязателен к переопределению).
- `model()` — экземпляр модели.
- `create(array $data)` — создание записи указанного класса.
- `getDataTableFilter(Request $request, DataTypeContract $dataType)` — базовый запрос для
  списка; переопределяйте его, чтобы добавить условия выборки, сортировки, отношения,
  ограничения по правам и т.д.

## Без класса-репозитория

Если единственное, что должен делать репозиторий, — сообщать класс модели (никакой своей
логики `getDataTableFilter`), заводить наследника не обязательно. Достаточно объявить
`$modelClass` на самом `DataType` — `BaseDataType::getRepository()` соберёт
`KY\AdminPanel\Repositories\ModelRepository` сам, и `__construct()` DataType-у не нужен:

```php
use App\Models\Post;
use KY\AdminPanel\DataTypes\BaseDataType;

class PostDataType extends BaseDataType
{
    protected ?string $modelClass = Post::class;

    // без __construct()
}
```

Если нужна своя логика запроса — по-прежнему заводите класс-репозиторий и назначайте его в
`__construct()`, как описано выше; существующие репозитории-наследники продолжают работать
без изменений.

## Зачем переопределять `getDataTableFilter`

Это единая точка, через которую проходит выборка для списка. Типичные случаи: показывать
только записи текущего пользователя, подгружать связи (`with`), применять глобальные
условия. Привязка фильтров к колонкам делается на стороне [полей](datatables.md)
(`->filter(...)`), а здесь формируется базовый запрос.
