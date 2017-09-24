<?php
/**
 * Created by PhpStorm.
 * User: Home-PC
 * Date: 28.07.2017
 * Time: 16:18
 */

namespace Yourify\Models\Scopes;


trait Active
{
    public function scopeActive():void{
        $query->whereNotNull('active');
    }
}