<?php
/**
 * Created by PhpStorm.
 * User: Home-PC
 * Date: 15.07.2017
 * Time: 12:48
 */

namespace Yourify\Repository;


use Illuminate\Database\Eloquent\Builder;
use Yourify\Repositories\AbstractRepository;
use Yourify\Repository\Contracts\ITagRepository;

class TagRepository extends AbstractRepository implements ITagRepository
{

    /**
     * @return string
     */
    public function model(): string
    {
        return Tag::class;
    }

    public function allActiveAndNotEmpty(): \Illuminate\Support\Collection
    {
        return $this->repository->whereHas('posts', function (Builder $query) {
            $query->select(['id']);
        })->active()->orderBy('updated_at', 'desc')
            ->get(['slug', 'name']);
    }
}