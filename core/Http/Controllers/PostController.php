<?php
/**
 * Created by PhpStorm.
 * User: Home-PC
 * Date: 05.09.2017
 * Time: 19:49
 */

namespace Yourify\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Contracts\Container\Container;
use Yourify\Http\Request\PostCommentsRequest;
use Yourify\Http\Request\PostCreateRequest;
use Yourify\Http\Request\PostEditRequest;
use Yourify\Http\Request\PostIndexRequest;
use Yourify\Repository\Contracts\ICategoryRepository;
use Yourify\Repository\Contracts\ICommentRepository;
use Yourify\Repository\Contracts\IPostRepository;
use Yourify\Repository\Contracts\ITagRepository;
use Yourify\Traits\CommonMethodsForController;

class PostController extends Controller{
    use CommonMethodsForController;

    /**
     * @var Container
     */
    protected $app;

    /**
     * PostController constructor.
     * @param Container $app
     */
    public function __construct(Container $app){
        $this->app = $app;

    }

    /**
     * Display a listing of the resource.
     *
     * @param PostIndexRequest $request
     * @param IPostRepository $postRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(PostIndexRequest $request, IPostRepository $postRepository)
    {
        $posts = $postRepository->customPaginate(
            $request->input('limit', 10),
            $request->input('page', 1),
            $request->input('category_id'),
            $request->input('author_id')
        );


        $meta = [];

        $breadcrumb = [];

        $breadcrumb[] = ['name' => trans('news::post.page.index.title')];

        $this->prepareBreadcrumbData($breadcrumb);

        $this->prepareHeaderData();
        $this->prepareMetaData($meta);
        $this->prepareTagsData();


        return view('news::post.index', [
            'auth' => auth(),
            'posts_paginate' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ICategoryRepository $categoryRepository
     * @param ITagRepository $tagRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(ICategoryRepository $categoryRepository, ITagRepository $tagRepository)
    {
        $this->middleware('auth');

        $this->prepareHeaderData();

        $breadcrumb = [];

        $breadcrumb[] = ['url' => route('news.index', [], false), 'name' => trans('news::post.page.index.title')];
        $breadcrumb[] = ['name' => trans('news::post.page.create.title')];

        $this->prepareBreadcrumbData($breadcrumb);

        $this->prepareMetaData(['title' => trans('news::post.page.create.title')]);

        $locales = [];

        foreach (config('translatable.locales') as $locale) {
            $locales[] = ['locale' => $locale];
        }

        return view('news::post.create', [
            'locale' => config('translatable.supported_locales')[$this->app->getLocale()]['regional'],
            'locales' => $locales,
            'hidden_fields' => [
                ['name' => 'user_id', 'value' => auth()->user()->id],
                ['name' => '_token', 'value' => csrf_token()],
            ],
            'form' => [
                'categories' => $categoryRepository->getAll(),
                'tags' => $tagRepository->getAll(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PostCreateRequest $request
     * @param IPostRepository $postRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PostCreateRequest $request, IPostRepository $postRepository)
    {
        $this->middleware('auth');


        $post = $postRepository->createNew();

        if ($request->has('published_now')) {

            $post->published_at = Carbon::now();

        } else {
            $post->published_at = $request->input('published_at');
        }

        $post->category_id = $request->input('category_id');
        $post->user_id = auth()->user()->id;

        $post->slug = $request->input('slug');


        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->keywords = $request->input('keywords');

        $post->name = $request->input('name');
        $post->summary = $request->input('summary');
        $post->story = $request->input('story');


        $post->save();


        if ($request->has('tags') && is_array($request->input('tags'))) {
            $post->tags()->sync($request->input('tags'));
        }


        \Session::flash('msg', trans('news::post.page.edit.message_edit_success'));

        return redirect()->route('news.index');
    }

    /**
     * @param int $id
     * @param string $slug
     * @param PostCommentsRequest $request
     * @param IPostRepository $postRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show(int $id, string $slug, PostCommentsRequest $request, IPostRepository $postRepository)
    {
        $post = $postRepository->show($id);

        if (is_null($post->published_at)) {
            abort(403);
        } elseif ($post->slug != $slug) {
            return redirect()->route('news.show', ['id' => $post->id, 'slug' => $post->slug]);
        }


        $post->setAttribute('views', $postRepository->viewsIncrement($id));

        $breadcrumb = [];

        $breadcrumb[] = [
            'url' => route('news.index', [], false),
            'name' => trans('news::post.page.index.title')
        ];

        $breadcrumb[] = ['url' => $post->category->url, 'name' => $post->category->name];
        $breadcrumb[] = ['name' => $post->name];

        $this->prepareBreadcrumbData($breadcrumb);



        $meta = [
            'title' => !is_null($post->title) ? $post->title : $post->name,
            'description' => !is_null($post->description) ? $post->description : str_limit($post->summary, 155, ''),
        ];

        if(!is_null($post->keywords)){
            $meta['keywords'] = $post->keywords;
        }

        $this->prepareMetaData($meta);
        $this->prepareHeaderData();
        $this->prepareTagsData();


        view()->composer('news::post.comment_add', function ($view) use ($id) {

            $hidden_fields = [];
            $hidden_fields[] = ['name' => 'post_id', 'value' => $id];

            $auth = auth();

            if ($auth->check()) {
                $hidden_fields[] = ['name' => 'user_id', 'value' => $auth->user()->id];
            }

            $locale = config('translatable.supported_locales')[$this->app->getLocale()]['regional'];

            return $view->with(compact('hidden_fields', 'locale', 'auth'));
        });


        return view('news::post.show', [
            'post' => $post,
            'comments' => $this->app->make(ICommentRepository::class)->paginateForPostID($id, $request->input('page', 1)),
        ]);
    }

    /**
     * @param int $id
     * @param IPostRepository $postRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit(int $id, IPostRepository $postRepository)
    {
        $this->middleware('auth');

        $auth = auth();

        if(!$auth->check()){
            return redirect()->route('news.index');
        }

        $post = $postRepository->findOrFail($id);


        $breadcrumb = [];

        $breadcrumb[] = ['url' => route('news.index', [], false), 'name' => trans('news::post.page.index.title')];
        $breadcrumb[] = ['name' => trans('news::post.page.edit.title')];

        $this->prepareBreadcrumbData($breadcrumb);

        $this->prepareMetaData(['title' => trans('news::post.page.edit.title')]);
        $this->prepareHeaderData();


        return view('news::post.edit', [
            'locale' => config('translatable.supported_locales')[$this->app->getLocale()]['regional'],
            'hidden_fields' => [
                ['name' => 'id', 'value' => $post->id],
                ['name' => 'user_id', 'value' => $auth->user()->id],
                ['name' => '_token', 'value' => csrf_token()],
                ['name' => '_method', 'value' => 'PUT'],
            ],
            'form' => [
                'categories' => $this->app->make(ICategoryRepository::class)->getAll(),
                'tags' => $this->app->make(ITagRepository::class)->getAll(),
            ],
            'post' => $post,
        ]);
    }

    /**
     * @param int $id
     * @param PostEditRequest $request
     * @param IPostRepository $postRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(int $id, PostEditRequest $request, IPostRepository $postRepository)
    {
        $this->middleware('auth');

        if ($id == $request->input('id')) {

            $post = $postRepository->findOrFail($id);

            if ($request->has('published_now')) {

                $post->published_at = Carbon::now();

            } else {
                $post->published_at = $request->input('published_at');
            }

            $post->category_id = $request->input('category_id');

            $post->slug = $request->input('slug');


            if ($request->has('tags') && is_array($request->input('tags'))) {
                $post->tags()->sync($request->input('tags'));
            } else {
                $post->tags()->sync([]);
            }


            $post->title = $request->input('title');
            $post->description = $request->input('description');
            $post->keywords = $request->input('keywords');

            $post->name = $request->input('name');
            $post->summary = $request->input('summary');
            $post->story = $request->input('story');

            $post->save();


            \Session::flash('msg', trans('news::post.page.edit.message_edit_success'));
        }

        return redirect()->route('news.index');
    }

}