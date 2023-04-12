<?php

namespace KY\AdminPanel\Traits\Controllers;

use AdminPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait HandleRelation
{
    /**
     * Get relations data.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function relation(Request $request)
    {
        $page       = $request->input('page');
        $on_page    = $request->input('on_page', 50);
        $search     = $request->input('search', false);
        $method     = $request->input('method', 'create');

        $model = $this->dataType->getModel();
        if (!in_array($method,['create','list'])) {
            $model = $model->find($request->input('id'));
        }
        $this->authorize($method, $model);

        $fields = $this->dataType->getFormFields($method);

        foreach ($fields as $field) {
            if ($field->get('name') === $request->input('field')) {
                $relationModel = app($field->get('relatedModel'));
                $skip = $on_page * ($page - 1);
                // If search query, use LIKE to filter results depending on field label
                if ($search) {
                    // If we are using additional_attribute as label
                    $total_count = $relationModel->where($field->get('displayedField'), 'LIKE', '%'.$search.'%')->count();
                    $relationOptions = $relationModel->take($on_page)->skip($skip)
                        ->where($field->get('displayedField'), 'LIKE', '%'.$search.'%')
                        ->get();
                } else {
                    $total_count = $relationModel->count();
                    $relationOptions = $relationModel->take($on_page)->skip($skip)->get();
                }

                $results = [];
                if (!$field->get('required') && !$search && $page == 1) {
                    $results[] = [
                        'id'   => '',
                        'text' => ap_trans('common.none'),
                    ];
                }

                // Sort results
                if (!empty($field->get('sortField'))) {
                    if (!empty($field->get('sortDirection')) && strtolower($field->get('sortDirection')) == 'desc') {
                        $relationOptions = $relationOptions->sortByDesc($field->get('sortField'));
                    } else {
                        $relationOptions = $relationOptions->sortBy($field->get('sortField'));
                    }
                }

                foreach ($relationOptions as $relationOption) {
                    $results[] = [
                        'id'   => $relationOption->{$field->get('key')},
                        'text' => $relationOption->{$field->get('displayedField')},
                    ];
                }

                return response()->json([
                    'results'    => $results,
                    'pagination' => [
                        'more' => ($total_count > ($skip + $on_page)),
                    ],
                ]);
            }
        }
        // No result found, return empty array
        return response()->json([], 404);
    }

}
