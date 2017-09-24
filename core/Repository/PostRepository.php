<?php
/**
 * Created by PhpStorm.
 * User: Home-PC
 * Date: 15.07.2017
 * Time: 12:42
 */

namespace Yourify\Repository;


use Illuminate\Database\Eloquent\Relations\Relation;
use Yourify\Models\Post;
use Yourify\Repositories\AbstractRepository;

use Yourify\Repository\Contracts\IPostRepository;

class PostRepository extends AbstractRepository implements IPostRepository
{

    /**
     * @return string
     */
    public function model(): string
    {
        return Post::class;
    }

    public function viewsIncrement(int $id): int
    {
        return $this->repository->where('id','=',$id)->increment('views');
    }

    public function show(int $id)
    {
        return $this->repository->where('id', $id)
            ->published()
            ->with([
                'tags' => function (Relation $query) {
                    $query->select(['slug', 'name']);
                },
                'user' => function (Relation $query) {
                    $query->select(['id', 'name']);
                },
                'category' => function (Relation $query) {
                    $query->select(['id', 'slug', 'name']);
                }
            ])
            ->firstOrfail([
                'id',
                'slug',
                'user_id',
                'created_at',
                'updated_at',
                'published_at',
                'category_id',
                'title',
                'description',
                'keywords',
                'name',
                'summary',
                'story',
                'views'
            ]);
    }

    public function costumPaginate(int $limit, int $page, int $category_id, int $author_id): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        if ($page < 1) {
            $page = 1;
        }

        if ($limit < 10) {
            $limit = 10;
        }

        // query for page
        $post_query = $this->repository->with([
            'tags' => function (Relation $query) {
                $query->select(['slug', 'name']);
            },
            'user' => function (Relation $query) {
                $query->select(['id', 'name']);
            },
            'category' => function (Relation $query) {
                $query->select(['id', 'slug', 'name']);
            }
        ])
            ->published()
            ->orderBy('published_at', 'desc')
            ->skip($limit * ($page - 1))
            ->take($limit);


        // query for count all
        $post_query_all = $this->repository->published();


        // build appends
        $appends = [];

        if ($page > 1) {
            $appends['page'] = $page;
        }

        if ($limit > 10) {
            $appends['limit'] = $limit;
        }

        // add conditions for query and appends for url

        if (!is_null($author_id)) {

            $appends['author_id'] = $author_id;

            $post_query->where('user_id', $author_id);

            $post_query_all->where('user_id', $author_id);
        }


        if (!is_null($category_id)) {

            if (\Request::route()->getName() !== 'category.show') {
                $appends['category_id'] = $category_id;
            }

            $post_query->where('category_id', $category_id);

            $post_query_all->where('category_id', $category_id);
        }


        // do queries
        $posts = $post_query->get([
            'id',
            'slug',
            'user_id',
            'published_at',
            'category_id',
            'name',
            'summary',
        ]);


        return new LengthAwarePaginator($posts, $post_query_all->get([\DB::raw(1)])->count(), $limit, $page,
            ['path' => Paginator::resolveCurrentPath(), 'query' => $appends]);
    }

    public function tagsPaginate(
        int $limit,
        int $page,
        int $category_id,
        int $author_id,
        ?string $tag
    ): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        if ($page < 1) {
            $page = 1;
        }

        if ($limit < 10) {
            $limit = 10;
        }

        // query for page
        $post_query = $this->repository->with([
            'tags' => function (Relation $query) {
                $query->select(['slug', 'name']);
            },
            'user' => function (Relation $query) {
                $query->select(['id', 'name']);
            },
            'category' => function (Relation $query) {
                $query->select(['id', 'slug', 'name']);
            }
        ])
            ->published()
            ->orderBy('published_at', 'desc')
            ->skip($limit * ($page - 1))
            ->take($limit);


        // query for count all
        $post_query_all = $this->repository->published();


        // build appends
        $appends = [];

        if ($page > 1) {
            $appends['page'] = $page;
        }

        if ($limit > 10) {
            $appends['limit'] = $limit;
        }

        // add conditions for query and appends for url

        if (!is_null($author_id)) {

            $appends['author_id'] = $author_id;

            $post_query->where('user_id', $author_id);

            $post_query_all->where('user_id', $author_id);
        }


        if (!is_null($category_id)) {

            if (\Request::route()->getName() !== 'category.show') {
                $appends['category_id'] = $category_id;
            }

            $post_query->where('category_id', $category_id);

            $post_query_all->where('category_id', $category_id);
        }


        // do queries
        $posts = $post_query->get([
            'id',
            'slug',
            'user_id',
            'published_at',
            'category_id',
            'name',
            'summary',
        ]);


        return new LengthAwarePaginator($posts, $post_query_all->get([\DB::raw(1)])->count(), $limit, $page,
            ['path' => Paginator::resolveCurrentPath(), 'query' => $appends]);
    }
    public function popularPageMain(int $limit): \Illuminate\Support\Collection
    {
        return $this->repository->with([
            'tags' => function (Relation $query) {
                $query->select(['slug', 'name']);
            },
            'user' => function (Relation $query) {
                $query->select(['id', 'name']);
            },
        ])
            ->published()
            ->orderBy('views', 'desc')
            ->skip(0)->take($limit)
            ->get(['id',
                'slug',
                'user_id',
                'published_at',
                'name',
                'summary']);
    }

    public function latestPageMain(int $limit): \Illuminate\Support\Collection
    {
        return $this->repository->published()
            ->orderBy('published_at', 'desc')
            ->skip(0)->take($limit)
            ->get(['id', 'slug', 'published_at', 'name']);
    }
}