<?php

namespace KY\AdminPanel\DataTypes;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use KY\AdminPanel\Blocks\Card;
use KY\AdminPanel\FormFields\ArrayBuilder;
use KY\AdminPanel\FormFields\Hidden;
use KY\AdminPanel\FormFields\Status;
use KY\AdminPanel\FormFields\Text;
use KY\AdminPanel\Repositories\SefRepository;
use KY\AdminPanel\Support\ArrayBuilderElement;
use KY\AdminPanel\Support\ArrayBuilderRule;

class SefDataType extends BaseDataType
{
    protected string $pluralTitle = 'ЧПУ';

    protected string $singleTitle = 'ЧПУ';

    protected string $slug = 'sef';

    public function __construct()
    {
        $this->repository = new SefRepository;
    }

    public function layout(): Collection
    {
        return collect([
            Card::blocks(
                'url', 'alias', 'get_params'
            ),
        ]);
    }

    public function fields(): Collection
    {
        return collect([
            Hidden::make('id')
                ->label('#')
                ->columnWidth('5%'),
            Text::make('url')
                ->label('Url')
                ->instruction('(без домена, первого и последнего слешей, например - contacts)')
                ->required(),
            Text::make('alias')
                ->label('Алиас')
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
                ->label('GET-параметры')
                ->displayValue('return item.name + "=" + item.value')
                ->viewCell('adminpanel::datatypes.seo.get_params.cell'),

            Status::make('status')
                ->columnWidth('5%'),
        ]);
    }

    public function rules(Request $request): array
    {
        return [
            'url' => ['required', 'max:255', Rule::unique('sef', 'url')->ignore($request->route('sef'))],
            'alias' => ['required', 'max:255'],
        ];
    }
}
