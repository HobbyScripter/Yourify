<?php

namespace Yourify\Repositories;

use Illuminate\Database\Eloquent\Model;


abstract class AbstractRepository implements IRepository
{
    protected $repository;
    /**
     * @return string
     */
    abstract public function model(): string;

    /**
     * AbstractRepository constructor.
     */
    public function __construct()
    {
        $this->makeModel();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     * @throws RepositoryException
     */
    protected function makeModel(): \Illuminate\Database\Eloquent\Model
    {
        $model = app()->make($this->model());
        if (!$model instanceof Model) {
            throw new RepositoryException(trans('news::common.messages.repository_model_instance', ['model'=> $this->model()]));
        }
        return $this->repository = $model;
    }

    /**
     * @param int|string $id
     * @return \Illuminate\Database\Eloquent\Builder|Model|null
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param int|string $id
     * @return \Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function findOrFail($id)
    {
        return $this->repository->findOrFail($id);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function findByKey(string $key, $value): \Illuminate\Database\Eloquent\Builder
    {
        return $this->repository->where($key, $value);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAll(): \Illuminate\Support\Collection
    {
        return $this->repository->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getActive(): \Illuminate\Support\Collection
    {
        return $this->repository->whereNotNull('active')->get();
    }

    /**
     * @param int|null $perPage
     * @param array $columns
     * @param string $pageName
     * @param int|null $page
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function simplePaginate($perPage = 12, $columns = ['*'], $pageName = 'page', $page = null): \Illuminate\Contracts\Pagination\Paginator
    {
        return $this->repository->simplePaginate($perPage, $columns, $pageName, $page);
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @param string $pageName
     * @param null $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = 12, $columns = ['*'], $pageName = 'page', $page = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createNew(): \Illuminate\Database\Eloquent\Model
    {
        return new $this->repository;
    }

    /**
     * @param int|string|array $id
     * @return bool
     */
    public function destroy($id): bool
    {
        return $this->repository->destroy($id);
    }
}