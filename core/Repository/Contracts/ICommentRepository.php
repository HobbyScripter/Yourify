<?php
namespace Yourify\Repository\Contracts;

use Yourify\Repositories\Contracts\IRepository;

interface ICommentRepository extends IRepository
{

    public function latestPageMain(int $limit = 5):\Illuminate\Support\Collection;

    public function paginateForPostID(int $id, int $page = 1, int $limit = 10):\Illuminate\Contracts\Pagination\LengthAwarePaginator;
    
    public function paginateIndex(int $page = 1, int $post_id = null, int $author_id = null, int $limit = 10):\Illuminate\Contracts\Pagination\LengthAwarePaginator;
    
}

