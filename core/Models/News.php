<?php

namespace Yourify\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Yourify\News
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $author_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $desc
 * @property-read \Yourify\User $user
 * @method static \Illuminate\Database\Query\Builder|\Yourify\News whereAuthorId($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\News whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\News whereDesc($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\News whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\News whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\News whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\News whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class News extends Model
{
    protected $table = 'news';

    protected $fillable = ['author_id', 'title', 'slug', 'body', 'excerpt', 'published_at'];

    protected $dates = ['published_at'];


    public function user(){
        return $this->belongsTo(User::class,'author_id');
    }


}
