<?php
/**
 * Created by PhpStorm.
 * User: Home-PC
 * Date: 28.07.2017
 * Time: 16:45
 */

namespace Yourify\Models;


use Illuminate\Database\Eloquent\Model;
use Yourify\Models\Scopes\Active;

class Category extends Model
{
    use Active;

    protected $table = 'categories';
    protected $fillable = [
        'slug',
        'name',
        'active'
    ];
    protected $hidden = ['created_at', 'updated_at', 'active'];
    protected $casts = [
        'slug' => 'string',
        'name' => 'string',
        'active' => 'boolean',
    ];
    protected $appends = [
        'url',
    ];
    public function getUrlAttribute($absolute = false):string
    {
        $url = route('category.show', ['slug' => $this->getAttribute('slug')], $absolute);
        $this->setAttribute('url', $url);
        return $url;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id', $this->getKeyName());
    }
}