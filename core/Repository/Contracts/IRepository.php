<?php
namespace Yourify\Repositories\Contracts;
/**
 * Created by PhpStorm.
 * User: Home-PC
 * Date: 08.07.2017
 * Time: 15:22
 */
interface IRepository
{
    public function find($id);

    public function findOrFail($id);
    public function findByKey(string $key, $value):\Illuminate\Database\Eloquent\Builder;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAll():\Illuminate\Support\Collection;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getActive():\Illuminate\Support\Collection;

    /**
     * @param int|null $perPage
     * @param array $columns
     * @param string $pageName
     * @param int|null $page
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function simplePaginate($perPage, $columns, $pageName, $page):\Illuminate\Contracts\Pagination\Paginator;

    /**
     * @param int|null $perPage
     * @param array $columns
     * @param string $pageName
     * @param int|null $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage, $columns, $pageName, $page):\Illuminate\Contracts\Pagination\LengthAwarePaginator;

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createNew():\Illuminate\Database\Eloquent\Model;

    /**
     * @param int|string|array $id
     * @return bool
     */
    public function destroy($id): bool;
}