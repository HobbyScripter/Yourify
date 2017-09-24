<?php
namespace Yourify\Repository\Contracts;

use Yourify\Repositories\Contracts\IRepository;

interface ICategoryRepository extends IRepository
{
    
    public function FindBySlug(string $slug);
    
    
    public function managePaginate(int $limit):\Illuminate\Contracts\Pagination\LengthAwarePaginator;
    
}

