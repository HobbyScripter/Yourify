<?php
namespace Yourify\Repository\Contracts;

use Yourify\Repositories\Contracts\IRepository;

interface ITagRepository extends IRepository
{
    
    public function allActiveAndNotEmpty():\Illuminate\Support\Collection;
    
}

