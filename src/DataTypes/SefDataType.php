<?php

namespace KY\AdminPanel\DataTypes;

use AdminPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use KY\AdminPanel\Blocks\Card;
use KY\AdminPanel\Blocks\Col;
use KY\AdminPanel\Blocks\Row;
use KY\AdminPanel\Repositories\SefRepository;
use KY\AdminPanel\FormFields\{ArrayBuilder, Hidden, Relation, Status, Text, Field};
use KY\AdminPanel\Http\Controllers\RoleContentController;
use KY\AdminPanel\Support\ArrayBuilderElement;
use KY\AdminPanel\Support\ArrayBuilderRule;

class SefDataType extends BaseDataType
{
    protected string $pluralTitle = 'ЧПУ';
    protected string $singleTitle = 'ЧПУ';

    protected string $slug = 'sef';

    public function __construct(){
        $this->repository = new SefRepository();
    }

    public function layout(): Collection
    {
        return collect([
            Card::blocks(
                'url','alias','get_params'
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
                ->instruction('(без домена, первого и последнего слешей, например - contacts)')
                ->required(),
            Text::make("alias")
                ->label("Алиас")
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

            Status::make('status')
                ->columnWidth('5%'),
        ]);
    }

    public function rules(Request $request):array
    {
        return [
            'name' => ['required', 'min:3', 'max:255',"unique:roles,name,{$request->route('role')},id"],
            'display_name' => ['required', 'min:3', 'max:255'],
        ];
    }



}
