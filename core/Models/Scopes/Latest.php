<?php
/**
 * Created by PhpStorm.
 * User: Home-PC
 * Date: 28.07.2017
 * Time: 16:21
 */

namespace Yourify\Models\Scopes;


trait Latest
{
    public function scopeLatest($query){
        $query->orderBy('updated_at', 'desc')->orderBy('created_at','desc');
    }
}