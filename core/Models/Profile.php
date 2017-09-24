<?php

namespace Yourify\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Yourify\Profile
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Yourify\Profile $user
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Profile whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Profile whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Profile whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Profile wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Profile whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Profile whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Profile extends Model
{
    protected $table = 'users';

    public function user(){
        return $this->belongsTo($this);
    }
}
