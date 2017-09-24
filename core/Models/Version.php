<?php

namespace Yourify\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Yourify\Version
 *
 * @mixin \Eloquent
 */
class Version extends Model
{
    protected $table = 'settings';

    public function get_version(){
        $version = config('version.versions');
        return $version;
    }

    public function show_version(){
        $istrue = config('version.show_version');
        return $istrue;
    }
}
