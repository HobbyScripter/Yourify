<?php
namespace Yourify\Repository;

use Yourify\Repositories\AbstractRepository;
use Repository\Contracts\ICategoryRepository;

class CategoryRepository extends AbstractRepository implements ICategoryRepository
{

    public function model():string{
        return Category::class;
    }
    
    public function getAll():\Illuminate\Support\Collection{
        return $this->repository->orderBy('updated_at', 'desc')->get(['id','name']);
    }
    
    public function FindBySlug(string $slug){
        return $this->repository->where('slug',$slug)->firstOfFail(['id','slug','name','active']);
    }
    
    public function managePaginate(int $limit = 10):\Illuminate\Contracts\Pagination\LengthAwarePaginator{
        return $this->repository->orderBy('updated_at','desc')->paginate($limit,['id','slug','name','active']);
    }
}

