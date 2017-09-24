<?php
/**
 * Created by PhpStorm.
 * User: Home-PC
 * Date: 28.07.2017
 * Time: 16:23
 */

namespace Yourify\Models\Scopes;


use Carbon\Carbon;

trait Published
{
    public function scopePublished($query, $time_now = null){
        if(is_null($time_now)){
            $query = Carbon::now();
        }
        $query->whereNotNull('published_at')->where('published_at','<=',$time_now);
    }
}