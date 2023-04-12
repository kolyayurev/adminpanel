<?php

namespace KY\AdminPanel\DataTypes;

use AdminPanel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use KY\AdminPanel\FormFields\{Hidden, Password, Relation, Text, Field, TextArea};
use KY\AdminPanel\Blocks\Card;
use KY\AdminPanel\Blocks\Col;
use KY\AdminPanel\Blocks\Row;
use KY\AdminPanel\Http\Controllers\UserContentController;
use KY\AdminPanel\Policies\UserPolicy;
use KY\AdminPanel\Repositories\UserRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserDataType extends BaseDataType
{
    protected string $title = 'Пользователи';
    protected string $slug = 'users';
    protected string $table = 'users';
    protected string $policy = UserPolicy::class;


    public function __construct(){
        $this->repository = new UserRepository();
    }

    public function layout(): Collection
    {
        return collect([
            Row::blocks(
                Col::blocks(
                    Card::blocks('id','name','email','role_id')
                )->md(6),
                Col::blocks(
                    Card::blocks('current_password','password','password_confirmation')->class('mt-4 mt-md-0')
                )->md(6),
                Col::blocks(
                    Card::blocks('*')->class('mt-4')
                )->visibleOnlyWhenHasFields(),
            ),
        ]);
    }

    public function fields():Collection{
        return collect([
            Hidden::make("id")
                ->label("#")
                ->columnWidth('5%'),
            Text::make("name")
                ->label("Логин")
                ->required(),
            Text::make("email")
                ->label("Email")
                ->type('email')
                ->required(),
            Password::make("current_password")
                ->label("Текущий пароль")
                ->hiddenOn(['list','create','show']),
            Password::make("password")
                ->label("Пароль")
                ->hiddenOn(['list','show'])
                ->default('1234'),
            Password::make("password_confirmation")
                ->label("Подтверждение пароля")
                ->hiddenOn(['list','show']),
            Relation::make("role_id")
                ->label("Роль")
                ->belongsTo($this->getModel(),AdminPanel::model('Role'))
                ->displayedField("display_name")
                ->required(),
        ]);
    }

    public function rules(Request $request):array
    {
        return [
            'email' => ['required', 'email', "unique:users,email,{$request->route('user')},id"],
            'name' => ['required', 'min:3', 'max:255'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'password' => 'nullable|required_with:password_confirmation|string|confirmed',
            'current_password' => [
                'nullable',
                Rule::requiredIf(!empty($request->id)&&!empty($request->password)),
                function ($attribute, $value, $fail) use ($request){
                    $user = AdminPanel::model('User')->find($request->id);
                    if ( !Hash::check($value, $user->password) ) {
                        $fail('Your current password is incorrect.');
                    }
                },
            ],
        ];
    }

}
