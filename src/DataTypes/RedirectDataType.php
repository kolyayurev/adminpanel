<?php

namespace KY\AdminPanel\DataTypes;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use KY\AdminPanel\Blocks\Card;
use KY\AdminPanel\FormFields\ArrayBuilder;
use KY\AdminPanel\FormFields\Hidden;
use KY\AdminPanel\FormFields\Status;
use KY\AdminPanel\FormFields\Text;
use KY\AdminPanel\Repositories\RedirectRepository;
use KY\AdminPanel\Support\ArrayBuilderElement;
use KY\AdminPanel\Support\ArrayBuilderRule;

class RedirectDataType extends BaseDataType
{
    protected string $pluralTitle = 'Редиректы';

    protected string $singleTitle = 'Редирект';

    protected string $slug = 'redirects';

    public function __construct()
    {
        $this->repository = new RedirectRepository;
    }

    public function layout(): Collection
    {
        return collect([
            Card::blocks(
                'from', 'get_params', 'to'
            ),
        ]);
    }

    public function fields(): Collection
    {
        return collect([
            Hidden::make('id')
                ->label('#')
                ->columnWidth('5%'),
            Text::make('from')
                ->label('Откуда')
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
            Text::make('to')
                ->label('Куда')
                ->required(),
            Status::make('status')
                ->columnWidth('5%'),
        ]);
    }

    public function rules(Request $request): array
    {
        return [
            'from' => ['required'],
            'to' => ['required'],
        ];
    }
}
