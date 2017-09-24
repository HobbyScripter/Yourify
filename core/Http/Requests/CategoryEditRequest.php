<?php
/**
 * Created by PhpStorm.
 * User: Home-PC
 * Date: 16.07.2017
 * Time: 15:37
 */

namespace Yourify\Http\Request;


class CategoryEditRequest extends CategoryCreateRequest
{
    public function rules()
    {
        return array_merge($this->getRules(),['id' => 'required|integer']);
    }
    public function messages()
    {
        return array_merge(trans('news.category.validate_messages.create'),
            trans('news.category.validate_messages.update'));
    }
}