<?php

namespace KY\AdminPanel\Tests\Utils\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Validator;
use KY\AdminPanel\Contracts\DataTypeContract;
use KY\AdminPanel\Contracts\FormFieldContract;
use KY\AdminPanel\DataTables\Column;

trait CreatesDataTableTestDoubles
{
    protected function createDataTypeTestDouble(string $slug = 'posts', mixed $model = null): DataTypeContract
    {
        return new class($slug, $model) implements DataTypeContract
        {
            public function __construct(private readonly string $slug, private readonly mixed $model) {}

            public function getName(): string
            {
                return 'post';
            }

            public function getTitle(): string
            {
                return 'Posts';
            }

            public function getSlug(): string
            {
                return $this->slug;
            }

            public function getModel()
            {
                return $this->model;
            }

            public function getPolicy(): string
            {
                return '';
            }

            public function getController(): string
            {
                return '';
            }

            public function getIndexView(): string
            {
                return '';
            }

            public function getFormView(): string
            {
                return '';
            }

            public function getShowView(): string
            {
                return '';
            }

            public function validator(Request $request): Validator
            {
                return validator()->make([], []);
            }

            public function rules(Request $request): array
            {
                return [];
            }

            public function messages(): array
            {
                return [];
            }

            public function customAttributes(): array
            {
                return [];
            }

            public function layout(): Collection
            {
                return collect();
            }

            public function fields(): Collection
            {
                return collect();
            }

            public function getFormFields(string $type): Collection
            {
                return collect();
            }

            public function getColumns(): Collection
            {
                return collect();
            }

            public function getDataTable(Request $request): JsonResponse
            {
                return response()->json([]);
            }
        };
    }

    protected function createFormFieldTestDouble(string $name = 'title'): FormFieldContract
    {
        return new class($name) implements FormFieldContract
        {
            public function __construct(private readonly string $name) {}

            public function get(string $key, mixed $default = null): mixed
            {
                return $key === 'name' ? $this->name : $default;
            }

            public function render($dataType = null, $model = null, string $viewType = 'form')
            {
                return '';
            }

            public function createContent($dataType, $model, $type)
            {
                return '';
            }

            public function getSlug()
            {
                return $this->name;
            }

            public function prepareValueToSave(Request $request, $model)
            {
                return null;
            }

            public function afterSave(Request $request, $model)
            {
                return null;
            }

            public function toColumn(): Column
            {
                return Column::make($this->name);
            }
        };
    }

    protected function createModelTestDouble(int $id = 7): Model
    {
        $model = new class extends Model
        {
            public $timestamps = false;
        };

        $model->setAttribute($model->getKeyName(), $id);
        $model->exists = true;

        return $model;
    }

    protected function createQueryTestDouble(): object
    {
        return new class
        {
            public array $whereCalls = [];

            public array $orWhereCalls = [];

            public function where(string $column, string $operator, mixed $value): self
            {
                $this->whereCalls[] = compact('column', 'operator', 'value');

                return $this;
            }

            public function orWhere(string $column, string $operator, mixed $value): self
            {
                $this->orWhereCalls[] = compact('column', 'operator', 'value');

                return $this;
            }
        };
    }
}
