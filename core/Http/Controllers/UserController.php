<?php

namespace Yourify\Http\Controllers;



use Illuminate\Contracts\Container\Container;
use Yourify\Repository\Contracts\IUserRepository;
use Yourify\Traits\CommonMethodsForController;

class UserController extends Controller
{
    use CommonMethodsForController;
    protected $app;
    protected $users;

    public function __construct(Container $app){
        $this->app = $app;
        $this->prepareHeaderData();
        $this->prepareTagsData();
    }

    public function show(int $id, IUserRepository $repository){
        $user = $repository->show($id);
        $breadcrumb = [];
        $breadcrumb[] =['name' => trans('users.page.show.breadcrumb',['name' => $user->name])];
        $this->prepareBreadcrumbData($breadcrumb);
        $this->prepareMetaData(['title' => trans('users.page.show.meta.title',['name' => $user->name])]);
        return view('user.show', compact('user'));
    }
}
