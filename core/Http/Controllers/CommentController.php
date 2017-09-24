<?php

namespace Yourify\Http\Controllers;

use Illuminate\Contracts\Container\Container;
use Yourify\Traits\CommonMethodsForController;
use Yourify\Http\Request\CommentsIndexRequest;
use Yourify\Repository\Contracts\ICommentRepository;
use Yourify\Repository\Contracts\IUserRepository;

/**
 * Class CommentController
 * @package Sevenpluss\NewsCrud\Http\Controllers
 */
class CommentController extends Controller
{
    use CommonMethodsForController;

    /**
     * @var Container
     */
    protected $app;

    /**
     * CommentController constructor.
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
        $this->setPageName('comments');
    }

    /**
     * @param CommentsIndexRequest $request
     * @param CommentRepositoryInterface $commentRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(CommentsIndexRequest $request, ICommentRepository $commentRepository)
    {
        $category_name = null;

        if($request->input('author_id')){
            $category_name = $this->app->make(IUserRepository::class)->getName($request->input('author_id'));
        }

        $category_name = !is_null($category_name) ? trans('news::comments.index.title_name', ['name'=> $category_name]) : trans('news::comments.index.title_all');


        $breadcrumb = [];

        $breadcrumb[] = [
            'url' => route('news.index', [], false),
            'name' => trans('news::post.page.index.title')
        ];

        $breadcrumb[] = ['name' => $category_name];

        $this->prepareBreadcrumbData($breadcrumb);


        $this->prepareMetaData(['title' => trans('news::category.index.title')]);
        $this->prepareHeaderData();


        return view('news::comments.index', [
            'auth' => auth(),
            'category_name' => $category_name,
            'comments' => $commentRepository->paginateIndex(
                $request->input('page', 1),
                $request->input('post_id'),
                $request->input('author_id'),
                $request->input('limit', 10)
            ),
        ]);
    }

}
