<?php

namespace Yourify\Models;

use Zizaco\Entrust\EntrustPermission;

/**
 * Yourify\Permission
 *
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Yourify\Models\Role[] $roles
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Models\Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Models\Permission whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Models\Permission whereDisplayName($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Models\Permission whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Models\Permission whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Yourify\Models\Permission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Permission extends EntrustPermission
{

}
