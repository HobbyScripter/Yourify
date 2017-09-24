<?php

namespace Yourify\Http\Request;

use \Illuminate\Foundation\Http\FormRequest;

class CategoryCreateRequest extends FormRequest
{

    public function authorize(){
        return true;
    }

    public function rules(){
        return $this->getRules();
    }

    protected function getRules(){
        return [
            'active' => 'sometimes|boolean',
            'slug' => ['sometimes','string','max:70','regex:/^[0-9a-zA-Z\-]+/'],
            'name' => 'required|max:100'
        ];
    }

    public function messages()
    {
        return trans('news.category.validate_massages.create');
    }

}