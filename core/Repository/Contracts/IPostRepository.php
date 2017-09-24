<?php
namespace Yourify\Repository\Contracts;

use Yourify\Repositories\Contracts\IRepository;

interface IPostRepository extends IRepository
{
    public function viewsIncrement(int $id):int;
    
    public function show(int $id);
    
    public function costumPaginate(int $limit,int $page,int $category_id,int $author_id):\Illuminate\Contracts\Pagination\LengthAwarePaginator;
    
    public function tagsPaginate(
        int $limit,
        int $page,
        int $category_id,
        int $author_id,
        ?string $tag
        ):\Illuminate\Contracts\Pagination\LengthAwarePaginator;
    
   public function popularPageMain(int $limit):\Illuminate\Support\Collection;
   
   public function latestPageMain(int $limit):\Illuminate\Support\Collection;
}

