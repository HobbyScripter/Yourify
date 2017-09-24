<?php
/**
 * Created by PhpStorm.
 * User: Home-PC
 * Date: 28.07.2017
 * Time: 18:43
 */

namespace Yourify\Http\Controllers;


use Illuminate\Contracts\Container\Container;
use Yourify\Http\Request\TagsIndexShowRequest;
use Yourify\Repository\Contracts\IPostRepository;
use Yourify\Repository\Contracts\ITagRepository;
use Yourify\Traits\CommonMethodsForController;

class TagController extends Controller
{
    use CommonMethodsForController;

    protected $app;
    public function __construct(Container $app){
        $this->app = $app;
        $this->setPageName('tags');
    }

    public function index(TagsIndexShowRequest $request, IPostRepository $repository){
        $category_name = trans('tags.page.title');

        $breadcrumb = [];
        $breadcrumb[] = ['name' => $category_name];
        $this->prepareBreadcrumbData($breadcrumb);

        $meta = ['title' => $category_name];
        $this->prepareMetaData($meta);
        $this->prepareHeaderData();
        $this->prepareTagsData();
        return view('tags.index_show',[
            'auth' => auth(),
            'current_route' => 'tags.index',
            'tag_archive' => null,
            'category_name' => $category_name,
            'posts_paginate' => $repository->costumPaginate(
                $request->input('limit',10),
                $request->input('page',1),
                $request->input('category_id'),
                $request->input('author_id')
            )
        ]);
    }

    public function show(string $slug, TagsIndexShowRequest $request,ITagRepository $tagRepository){
        $tag = $tagRepository->findByKey('slug',$slug)->firstOrFail(['id','slug','name','active']);
        if($tag->active = false){
            abort(403);
        }
        $posts = $this->app->make(IPostRepository::class)->tagsPaginate(
            $request->input('limit',10),
            $request->input('page',1),
            $request->input('category_id'),
            $request->input('author_id'),
            $slug
        );
        $meta = [
          'title' => trans('tags.meta.title_category', ['category' => $tag->name])
        ];

        $breadcrumb = [];
        $breadcrumb[] = ['name' => trans('tags.page.title_category',['category' => $tag->name])];

        $this->prepareBreadcrumbData($breadcrumb);

        $this->prepareMetaData($meta);
        $this->prepareHeaderData();
        $this->prepareTagsData($slug);

        return view('tags.index_show',[
            'auth' => auth(),
            'current_route' => 'tags.show',
            'tag' => $tag,
            'tag_name' => $tag->name,
            'tag_slug' => $slug,
            'posts_paginate' => $posts
            ]);
    }
}