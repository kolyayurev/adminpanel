<?php

namespace KY\AdminPanel\DataTypes;

use AdminPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use KY\AdminPanel\Blocks\Card;
use KY\AdminPanel\Blocks\Col;
use KY\AdminPanel\Blocks\Row;
use KY\AdminPanel\Policies\RolePolicy;
use KY\AdminPanel\Repositories\SeoRepository;
use KY\AdminPanel\FormFields\{ArrayBuilder, Hidden, Relation, Status, Text, Field, TextArea, TextEditor};
use KY\AdminPanel\Http\Controllers\RoleContentController;
use KY\AdminPanel\Repositories\RoleRepository;
use KY\AdminPanel\Support\ArrayBuilderElement;
use KY\AdminPanel\Support\ArrayBuilderRule;

class SeoDataType extends BaseDataType
{
    protected string $pluralTitle = 'Мета-информация для страниц';
    protected string $singleTitle = 'Мета-информация для страниц';

    protected string $slug = 'seo';

    public function __construct(){
        $this->repository = new SeoRepository();
    }

    public function layout(): Collection
    {
        return collect([
            Card::blocks(
                'url','get_params',
                Row::blocks(
                    Col::blocks('title','meta_description')->md(4),
                    Col::blocks('meta_og_title','meta_og_description')->md(4),
                    Col::blocks('h1','meta_keywords')->md(4),
                ),
                'seo_text'
            )

        ]);
    }

    public function fields():Collection{
        return collect([
            Hidden::make("id")
                ->label("#")
                ->columnWidth('5%'),
            Text::make("url")
                ->label("Url")
                ->instruction('(без домена и первого слеша, например - contacts)')
                ->required(),
            ArrayBuilder::make('get_params')
                ->fields(
                    ArrayBuilderElement::make('name')
                        ->label('Ключ')
                        ->rules(ArrayBuilderRule::make()->required()),
                    ArrayBuilderElement::make('value')
                        ->label('Значение')
                )
                ->label("GET-параметры")
                ->displayValue('return item.name + "=" + item.value')
                ->viewCell('adminpanel::datatypes.seo.get_params.cell'),
            Text::make("title")
                ->label("Title"),
            TextArea::make("meta_description")
                ->label("Meta-description")
                ->rows(5)
                ->hiddenOn(['list']),
            Text::make("meta_og_title")
                ->label("OG:Title")
                ->hiddenOn(['list']),
            TextArea::make("meta_og_description")
                ->label("OG:Description")
                ->rows(5)
                ->hiddenOn(['list']),
            Text::make("h1")
                ->label("H1")
                ->hiddenOn(['list']),
            TextArea::make("meta_keywords")
                ->label("Meta-keywords")
                ->rows(5)
                ->hiddenOn(['list']),
            TextEditor::make("seo_text")
                ->label("SEO-Text")
                ->hiddenOn(['list']),

            Status::make('status')
                ->columnWidth('5%'),
        ]);
    }

    public function rules(Request $request):array
    {
        return [
            'url' => ['required'],
        ];
    }



}
