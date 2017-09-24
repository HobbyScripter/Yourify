<?php
namespace Yourify\Repository\Contracts;

use Yourify\Repositories\Contracts\IRepository;

interface IUserRepository extends IRepository
{
    public function show(int $id);
    
    public function getname(int $user_id):?string;
}

