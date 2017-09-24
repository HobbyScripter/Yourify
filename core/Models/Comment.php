<?php
/**
 * Created by PhpStorm.
 * User: Home-PC
 * Date: 28.07.2017
 * Time: 16:43
 */

namespace Yourify\Models;


use Illuminate\Database\Eloquent\Model;
use Yourify\Models\Scopes\Latest;

class Comment extends Model
{
    use Latest;

    protected $table = 'comments';
    protected $primaryKey = 'id';
    protected $fillable = [
        'post_id',
        'user_id',
        'email',
        'name',
        'content'
    ];
    protected $casts = [
        'post_id' => 'integer',
        'user_id' => 'integer',
        'email' => 'string',
        'name' => 'string',
        'content' => 'string'
    ];

    public function posts():\Illuminate\Database\Eloquent\Relations\BelongsTo{
        return $this->belongsTo(Post::class,'post_id','id');
    }

    public function user():\Illuminate\Database\Eloquent\Relations\BelongsTo{
        return $this->belongsTo(User::class,'user_id','id');
    }
}