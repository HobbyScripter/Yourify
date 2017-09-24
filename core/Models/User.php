<?php

namespace Yourify\Models;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Zizaco\Entrust\Traits\EntrustUserTrait;

/**
 * Yourify\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Yourify\News[] $news
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\Yourify\Role[] $roles
 * @method static \Illuminate\Database\Query\Builder|\Yourify\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens;//, Activity;
    use EntrustUserTrait;

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected $dates = ['logged_in_at', 'logged_out_at'];

    protected $appends = ['url','url_posts'];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function news()
    {
        return $this->hasMany('Yourify\News');
    }

    public function isOnline(){
        return \Cache::has('user-is-online-'.$this->id);
    }

    public function addNewMember($request){
        $user = $this->create($request->all());
        $user->roles()->attach('user');
        return $user;
    }

    public function getUrlAttribute($absolute = false):string
    {
        $url = route('user.show', ['id' => $this->getAttribute('id')], $absolute);
        $this->setAttribute('url', $url);

        return $url;
    }
    public function getUrlPostsAttribute($absolute = false):string
    {
        $url = route('news.index', ['author_id' => $this->getAttribute('id')], $absolute);
        $this->setAttribute('url_posts', $url);

        return $url;
    }
    public function getUrlCommentsAttribute($absolute = false):string
    {
        $url = route('comments.index', ['author_id' => $this->getAttribute('id')], $absolute);
        $this->setAttribute('url_comments', $url);

        return $url;
    }
    public function posts():\Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class, 'user_id', $this->getKeyName());
    }
    public function comments():\Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class, 'user_id', $this->getKeyName());
    }
}
