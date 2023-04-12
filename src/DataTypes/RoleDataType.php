<?php

namespace KY\AdminPanel\DataTypes;

use AdminPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use KY\AdminPanel\Blocks\Card;
use KY\AdminPanel\Blocks\Col;
use KY\AdminPanel\Blocks\Row;
use KY\AdminPanel\Policies\RolePolicy;
use KY\AdminPanel\FormFields\{Relation, Text, Field};
use KY\AdminPanel\Http\Controllers\RoleContentController;
use KY\AdminPanel\Repositories\RoleRepository;

class RoleDataType extends BaseDataType
{
    protected string $title = 'Роли';
    protected string $slug = 'roles';
    protected string $table = 'roles';
    protected string $policy = RolePolicy::class;
    public function __construct(){
        $this->repository = new RoleRepository();
    }

    public function layout(): Collection
    {
        return collect([
            Row::blocks(
                Col::blocks(
                    Card::blocks('name','display_name')
                )->md(8),
                Col::blocks(
                    Card::blocks('users')->class('mt-4 mt-md-0')
                )->md(4),
                Col::blocks(
                    Card::blocks('*')->class('mt-4')
                )->visibleOnlyWhenHasFields(),
            ),
        ]);
    }

    public function fields():Collection{
        return collect([
            Text::make("name")
                ->label("Код"),
            Text::make("display_name")
                ->label("Название"),
            Relation::make("users")
                ->label("Пользователи")
                ->hasMany($this->getModel(),AdminPanel::model('User'))
                ->column('role_id')
                ->displayedField("name")
                ->hiddenOn(['create']),
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
