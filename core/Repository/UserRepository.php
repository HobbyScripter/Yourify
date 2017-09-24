<?php
/**
 * Created by PhpStorm.
 * User: Home-PC
 * Date: 15.07.2017
 * Time: 12:54
 */

namespace Yourify\Repository;


use Illuminate\Database\Eloquent\Relations\Relation;
use Yourify\Models\User;
use Yourify\Repositories\AbstractRepository;
use Yourify\Repository\Contracts\IUserRepository;

class UserRepository extends AbstractRepository implements IUserRepository
{

    /**
     * @return string
     */
    public function model(): string
    {
        return User::class;
    }

    public function show(int $id):User
    {
        return $this->repository->where('id',$id)->with([
            'posts' => function (Relation $query){
                $query->select(['user_id']);
            },
            'comments' => function (Relation $query) {
                $query->select(['user_id']);
            }
        ])->firstOrFail(['id','name']);
    }

    public function getname(int $user_id):?string
    {
        return $this->repository->where('id', $user_id)->first(['name'])->pluck('name')->first();
    }
}