<?php

namespace Yourify\Models;


use Zizaco\Entrust\EntrustRole;

/**
 * Yourify\Role
 *
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Yourify\Permission[] $perms
 * @property-read \Illuminate\Database\Eloquent\Collection|\Yourify\User[] $users
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Role whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Role whereDisplayName($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Role whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Role whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Role extends EntrustRole
{

}
