# Консольные команды

### Генерация DataType

```bash
php artisan adminpanel:make:datatype Post Post
```
Первый параметр это имя DataType, второе это имя класса Repository.

В папке App\AdminPanel\DataTypes появистя файл PostDataType.php


```php
<?php

namespace App\AdminPanel\DataTypes;

use App\AdminPanel\Repositories\PostRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use KY\AdminPanel\DataTypes\BaseDataType;

class PostDataType extends BaseDataType
{
    protected string $title = 'Post';
    protected string $slug = 'posts';

    public function __construct(){
        $this->repository = new PostRepository();
    }

    public static function layout(): Collection
    {
        return collect([
            Row::blocks('*'),
        ]);
    }

    public function fields():Collection{
        return collect([]);
    }

    public function rules(Request $request):array
    {
        return [];
    }
}
```

#### Поключение DataType

Чтобы подключить новый DataType в AdminPanel, необходимо его зарегистрировать в ServiceProvider

```php
<?php

namespace App\Providers;

use AdminPanel;
use App\AdminPanel\DataTypes\PostDataType;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    public function register()
    {
        AdminPanel::addDataType(PostDataType::class);
    }
}
```

### Генерация PageType

```bash
php artisan adminpanel:make:pagetype About 
```
Первый параметр это имя PageType

В папке App\AdminPanel\PageTypes появистя файл AboutPageType.php


```php
<?php

namespace App\AdminPanel\PageTypes;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use KY\AdminPanel\Blocks\{Accordion,Collapse,Row};
use KY\AdminPanel\PageTypes\BasePageType;

class AboutPageType extends BasePageType
{
    protected string $title = 'About';
    protected string $slug = 'about';


    /**
     * @return Collection
     */
    public static function layout(): Collection
    {
        return collect([
            Accordion::blocks(
                Collapse::blocks(
                    Row::blocks(
                        '*'
                    )
                ),
            )
        ]);
    }

    public function fields():Collection{
        return collect([]);
    }

}
```

#### Поключение PageType

Чтобы подключить новый PageType в AdminPanel, необходимо его зарегистрировать в ServiceProvider

```php
<?php

namespace App\Providers;

use AdminPanel;
use App\AdminPanel\PageTypes\AboutPageType;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    public function register()
    {
        AdminPanel::addPageType(AboutPageType::class);
    }
}
```

### Генерация Repository

```bash
php artisan adminpanel:make:repository Post  Post
```

Первый параметр это имя Repository, второе это имя модели в App\Models .

В папке App\AdminPanel\Repositories появистя файл PostRepository.php

```php
<?php

namespace App\AdminPanel\Repositories;

use App\Models\Post;
use Illuminate\Http\Request;
use KY\AdminPanel\Repositories\BaseRepository;

class PostRepository extends BaseRepository
{
    public function modelClass():string
    {
        return Post::class;
    }

    public function getDataTableFilter(Request $request)
    {
        $query = $this->model->query();

        return $query;
    }

}
```

### Генерация DataController

```bash
php artisan adminpanel:make:datacontroller Post  Post
```

Первый параметр это имя DataController, второе это имя DataType.

В папке App\AdminPanel\Controllers появистя файл PostDataController.php

```php
<?php

namespace App\AdminPanel\Controllers;

use AdminPanel;
use Illuminate\Http\Request;
use KY\AdminPanel\Http\Controllers\BaseDataController;

class PostDataController extends BaseDataController
{
    public function __construct(){
        $this->dataType = AdminPanel::getDataType('posts');
    }

}
```
Этот контроллер вы можете подключить в своем PostDataType в качестве обработчика.

```php

<?php

namespace App\AdminPanel\DataTypes;

use  App\AdminPanel\Controllers\PostDataController;

class PostDataType extends BaseDataType
{
    ...
    protected string $controller = PostDataController::class;
    ...
 }

```
