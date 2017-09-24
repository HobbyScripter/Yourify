<?php

namespace Yourify\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Yourify\Admin
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Admin whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Admin whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Admin whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Admin wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Admin whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = "users";

    protected $fillable = [
        'name','password'
    ];

    protected $hidden = [
        'password','remember_password'
    ];
}
