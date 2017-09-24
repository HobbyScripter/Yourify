<?php

namespace Yourify\Http\Controllers;

use Illuminate\Contracts\Container\Container;
use Yourify\Http\Request\PostIndexRequest;
use Yourify\Http\Request\CategoryCreateRequest;
use Yourify\Http\Request\CategoryEditRequest;
use Yourify\Traits\CommonMethodsForController;
use Yourify\Repository\Contracts\IPostRepository;
use Yourify\Repository\Contracts\ICategoryRepository;

/**
 * Class CategoryController
 * @package Sevenpluss\NewsCrud\Http\Controllers
 */
class CategoryController extends Controller
{
    use CommonMethodsForController;

    /**
     * @var Container
     */
    protected $app;

    /**
     * CategoryController constructor.
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
        $this->setPageName('categories');
    }

    /**
     * Display a listing of the resource.
     *
     * @param CategoryRepositoryInterface $categoryRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ICategoryRepository $categoryRepository)
    {
        $this->setPageName('category_crud');
        $this->prepareMetaData(['title' => trans('news::category.index.title')]);
        $this->prepareHeaderData();

        $breadcrumb = [];
        $breadcrumb[] = ['name' => trans('news::category.index.title')];

        $this->prepareBreadcrumbData($breadcrumb);

        return view('news::category_crud.index', ['categories' => $categoryRepository->managePaginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $this->prepareMetaData(['title' => trans('news::category.create.title')]);
        $this->prepareHeaderData();

        $breadcrumb = [];
        $breadcrumb[] = ['url' => route('category.index', [], false), 'name' => trans('news::category.index.title')];
        $breadcrumb[] = ['name' => trans('news::category.create.title')];

        $this->prepareBreadcrumbData($breadcrumb);

        $locales = [];

        foreach (config('translatable.locales') as $locale) {
            $locales[] = ['locale' => $locale];
        }

        return view('news::category_crud.create', [
            'locale' => config('translatable.supported_locales')[$this->app->getLocale()]['regional'],
            'locales' => $locales,
            'hidden_fields' => [
                ['name' => '_token', 'value' => csrf_token()],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryCreateRequest $request
     * @param CategoryRepositoryInterface $categoryRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CategoryCreateRequest $request, ICategoryRepository $categoryRepository)
    {
        $this->prepareHeaderData();

        $category = $categoryRepository->createNew();

        $category->active = $request->input('active');

        $category->slug = $request->input('slug');

        $category->name = $request->input('name');

        $category->save();

        \Session::flash('msg', trans('news::category.edit.message_add_success'));

        return redirect()->route('category.index');
    }

    /**
     * @param PostIndexRequest $request
     * @param string $slug
     * @param CategoryRepositoryInterface $categoryRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(PostIndexRequest $request, string $slug, ICategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->findBySlug($slug);

        if ($category->active==false){
            abort(403);
        }


        $this->setCurrentCategoryName($slug);

        $breadcrumb = [];
        $breadcrumb[] = ['url' => route('news.index', [], false), 'name' => trans('news::post.page.index.title')];
        $breadcrumb[] = ['name' => $category->name];

        $this->prepareBreadcrumbData($breadcrumb);

        $meta = ['title' => trans('news::post.meta.title_category', ['category' => $category->name])];

        $this->prepareMetaData($meta);
        $this->prepareHeaderData();
        $this->prepareTagsData();


        return view('news::category.show', [
            'auth' => auth(),
            'category' => $category,
            'posts_paginate' => $this->app->make(IPostRepository::class)->customPaginate(
                $request->input('limit', 10),
                $request->input('page', 1),
                $category->id,
                $request->input('author_id')
            ),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @param CategoryRepositoryInterface $categoryRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $id, ICategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->findOrFail($id);


        $breadcrumb = [];

        $breadcrumb[] = ['url' => route('category.index', [], false), 'name' => trans('news::category.index.title')];
        $breadcrumb[] = ['name' => trans('news::category.edit.title')];

        $this->prepareBreadcrumbData($breadcrumb);

        $this->prepareMetaData(['title' => trans('news::category.edit.title')]);


        return view('news::category_crud.edit', [
            'locale' => config('translatable.supported_locales')[$this->app->getLocale()]['regional'],
            'hidden_fields' => [
                ['name' => 'id', 'value' => $category->id],
                ['name' => '_token', 'value' => csrf_token()],
                ['name' => '_method', 'value' => 'PUT'],
            ],
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryEditRequest $request
     * @param int $id
     * @param CategoryRepositoryInterface $categoryRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CategoryEditRequest $request, int $id, ICategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->findOrFail($id);

        $category->active = $request->input('active');

        $category->slug = $request->input('slug');

        $category->name = $request->input('name');

        $category->save();

        \Session::flash('msg', trans('news::category.edit.message_edit_success'));

        return redirect()->route('category.index');
    }
}
