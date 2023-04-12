<?php

namespace KY\AdminPanel\DataTypes;

use AdminPanel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use KY\AdminPanel\FormFields\{Alias,
    ArrayBuilder,
    Checkbox,
    Gallery,
    Hidden,
    Image,
    ListField,
    MediaPicker,
    Relation,
    Text,
    Field,
    TextArea,
    TextEditor};
use KY\AdminPanel\Http\Controllers\TestContentController;
use KY\AdminPanel\Policies\TestPolicy;
use KY\AdminPanel\Repositories\TestRepository;
use KY\AdminPanel\Support\ArrayBuilderElement;
use KY\AdminPanel\Support\ArrayBuilderRule;
use KY\AdminPanel\Support\Trumbnail;

class TestDataType extends BaseDataType
{
    protected string $title = 'Тест';
    protected string $slug = 'test';

    protected string $policy = TestPolicy::class;

    public function __construct()
    {
        $this->repository = new TestRepository();
    }

    public function fields(): Collection
    {
        $users = array_column(AdminPanel::model('User')->get()->toArray(), 'name', 'id');

        return collect([
            Hidden::make("id")
                ->label("#")
                ->width('5%'),
            Text::make("text")
                ->label("text")
                ->required(),
            Alias::make("alias")
                ->source('text')
                ->forceUpdate(false)
                ->label("Alias"),
            TextEditor::make("ckeditor")
                ->label("TextEditor"),
            Checkbox::make("checkbox")
                ->label("Checkbox")
                ->default(true),
            TextArea::make("textarea")
                ->label("textarea")
                ->required(),
            ListField::make('list')
                ->label("List"),
            MediaPicker::make('media_picker')
                ->basePath('/test')
                ->showAsImage()
                ->max(2)
                ->label("Media picker")
                ->hideThumbnails()
                ->thumbnails(
                    Trumbnail::make('crop')
                        ->crop(400,200),
                ),
            Relation::make("user_id")
                ->label("Пользователь")
                ->belongsTo($this->getModel(),AdminPanel::model('User')),
            Relation::make("users")
                ->label("Пользователи")
                ->belongsToMany($this->getModel(), AdminPanel::model('User')),
            ArrayBuilder::make('array_builder')
                ->label("Array Builder")
                ->defaultFieldName('last_name')
                ->fields(
                    ArrayBuilderElement::make('last_name')
                        ->label('Фамилия')
                        ->default("Иванов")
                        ->rules(ArrayBuilderRule::make()->required())
                        ->col(6),
                    ArrayBuilderElement::make('first_name')
                        ->label('Имя')
                        ->default("Иван")
                        ->rules(ArrayBuilderRule::make()->required())
                        ->col(6),
                    ArrayBuilderElement::make('second_name')
                        ->label('Отчество')
                        ->default("Иванович")
                        ->col(6),
                )
                ->displayValue("return item.last_name+' '+item.first_name;"),
            Gallery::make('gallery')
//                ->mediaPicker(
//                    MediaPicker::make('url')
//                        ->dataType($this->getSlug())
//                )
                ->label("Gallery"),
//            Image::make("image")
//                ->label("Image")
//                ->quality(50)
//                ->resize(1000,400)
//                ->folder('test/{uid}/{key}/{date:d.m.Y}/{random:5}')
//                ->trumbnails(
//                    Trumbnail::make('mini scale')
//                        ->scale(60),
//                    Trumbnail::make('crop')
//                        ->crop(400,200),
//                )
        ]);
    }

    public function getRules(Request $request): array
    {
        return [
//            'title' => ['required', 'min:3', 'max:255'],
//            'alias' => ['required', 'string', "unique:posts,alias,{$request->route('user')},id"],
        ];
    }

}
