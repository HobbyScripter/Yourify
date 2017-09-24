<?php

namespace Yourify\Models;

use Illuminate\Database\Eloquent\Model;
use Kim\Activity\Activity;


/**
 * Yourify\Online
 *
 * @property int $id
 * @property int $user_id
 * @property string $ip_address
 * @property string $user_agent
 * @property string $payload
 * @property int $last_activity
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Online whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Online whereIpAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Online whereLastActivity($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Online wherePayload($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Online whereUserAgent($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Online whereUserId($value)
 * @mixin \Eloquent
 */
class Online extends Model
{
    public $table = 'sessions';

    public $timestamps = false;


}
