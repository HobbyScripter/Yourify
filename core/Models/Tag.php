<?php
/**
 * Created by PhpStorm.
 * User: Home-PC
 * Date: 28.07.2017
 * Time: 16:50
 */

namespace Yourify\Models;


use Illuminate\Database\Eloquent\Model;
use Yourify\Models\Scopes\Active;

class Tag extends Model
{
    use Active;
    protected $table = 'tags';
    protected $primaryKey = 'slug';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'slug',
        'name',
        'active',
    ];
    protected $hidden = ['created_at', 'updated_at', 'active'];
    protected $casts = [
        'slug' => 'string',
        'name' => 'string',
        'active' => 'boolean',
    ];
    protected $appends = [
        'url',
        'is_active',
    ];
    public function setActiveAttribute($value):void
    {
        if ((is_string($value) && strtolower($value) == 'yes')
            || (is_bool($value) && $value == true)
            || (is_int($value) && $value > 0)
        ) {
            $this->attributes['active'] = 1;
        } else {
            $this->attributes['active'] = null;
        }
    }
    public function getUrlAttribute($absolute = false):string
    {
        $url = route('tags.show', ['slug' => $this->getAttribute('slug')], $absolute);
        $this->setAttribute('url', $url);

        return $url;
    }
    public function getIsActiveAttribute(string $tag = null):bool
    {
        $is_active = !is_null($tag) && $tag == $this->getAttribute('slug');
        $this->setAttribute('is_active', boolval($is_active));

        return $is_active;
    }
    public function posts():\Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_tags', 'tag_slug', 'post_id');
    }
}