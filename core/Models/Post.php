<?php

namespace Yourify\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Yourify\Models\Scopes\Latest;
use Yourify\Models\Scopes\Published;

class Post extends Model
{
    use Published,Latest;

    protected  $table = 'posts';

    protected $dates =['published_at'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @var array
     */
    protected $casts = [
        'user_id' => 'interger',
        'category_id' => 'interger',
        'slug' => 'string',
        'title' => 'string',
        'description' => 'string',
        'keywords' => 'string',
        'name' => 'string',
        'summary' => 'string',
        'store' => 'string',
    ];

    /**
     * @var array
     */
    protected $appends = [
        'url',
        'is_published_today',
        'published_safe',
        'manage_buttons'
    ];

    public function getUrlAttribute($absolute = false):string{
        $url = route('news.show',['id' => $this->getAttribute('id'),'slug' => $this->getAttribute('slug')],$absolute);
        $this->setAttribute('url',$url);
        return $url;
    }

    public function getIsPublishedTodayAttribute(): bool
    {
        $time_now = Carbon::now();
        $is_today = $this->published_at->diff($time_now)->days < 1;
        $this->setAttribute('is_published_today', boolval($is_today));

        return $is_today;
    }

    public function getPublishedSafeAttribute(): string
    {
        $time = $this->published_at->format(trans($this->getAttribute('is_published_today') ? 'news::config.time' : 'news::config.datetime'));
        $this->setAttribute('published_safe', $time);

        return $time;
    }

    public function getManageButtonsAttribute():array
    {
        $buttons =  [
            'edit' => route('news.edit', ['slug' => $this->getAttribute('id')], false),
            'delete' => true,
        ];
        $this->setAttribute('manage_buttons', $buttons);

        return $buttons;
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags():\Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tags', 'post_id', 'tag_slug');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user():\Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category():\Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}