<?php
namespace Yourify\Repository;

use Repository\Contracts\ICommentRepository;
use Yourify\Repositories\AbstractRepository;

class CommentRepositry extends AbstractRepository implements ICommentRepository
{
    
    protected $repository;
    
    public function model():string{
        return Comment::class;
    }
    /**
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function latestPageMain(int $limit = 5): \Illuminate\Support\Collection
    {
        return $this->repository->orderBy('created_at', 'desc')
        ->with(['user' => function (Relation $query) {
            $query->select(['id', 'name']);
        }, 'post' => function (Relation $query) {
            $query->select(['id', 'slug']);
        }])
        ->skip(0)->take($limit)
        ->get([
            'id',
            'user_id',
            'post_id',
            'name',
            'created_at',
            'content'
        ]);
    }
    
    /**
     * @param int $id
     * @param int $page
     * @param int $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateForPostID(int $id, int $page = 1, int $limit = 10): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        if ($page < 1) {
            $page = 1;
        }
        
        $comments = $this->repository->where('post_id', $id)
        ->with(['user' => function (Relation $query) {
            $query->select(['id', 'name']);
        }])
        ->orderBy('created_at', 'desc')
        ->skip($limit * ($page - 1))
        ->take($limit)
        ->get([
            'id',
            'user_id',
            'name',
            'created_at',
            'content'
        ]);
        
        // query for count all
        $comments_all = $this->repository->where('post_id', $id)
        ->get([\DB::raw(1)])->count();
        
        // build paginator
        $appends = [];
        
        if ($page > 1) {
            $appends['page'] = $page;
        }
        
        return new LengthAwarePaginator($comments, $comments_all, $limit, $page,
            ['path' => Paginator::resolveCurrentPath(), 'query' => $appends]);
    }
    
    
    /**
     * @param int $page
     * @param int|null $post_id
     * @param int|null $author_id
     * @param int $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateIndex(int $page = 1, int $post_id = null, int $author_id = null, int $limit = 10): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        if ($page < 1) {
            $page = 1;
        }
        
        $query = $this->repository->with(['user' => function (Relation $query) {
            $query->select(['id', 'name']);
        }, 'post' => function (Relation $query) {
            $query->select(['id', 'slug']);
        }])
        ->orderBy('created_at', 'desc')
        ->skip($limit * ($page - 1))
        ->take($limit);
        
        $query_all = $this->repository->whereNotNull('post_id');
        
        
        $appends = [];
        
        if (!is_null($author_id)) {
            
            $appends['author_id'] = $author_id;
            
            $query->where('user_id', $author_id);
            $query_all->where('user_id', $author_id);
        }
        
        
        if (!is_null($post_id)) {
            
            $appends['post_id'] = $post_id;
            
            $query->where('post_id', $post_id);
            $query_all->where('post_id', $post_id);
        }
        
        
        $comments = $query->get([
            'id',
            'post_id',
            'user_id',
            'name',
            'created_at',
            'content'
        ]);
        
        // query for count all
        $comments_all = $query_all->get([\DB::raw(1)])->count();
        
        // build paginator
        if ($page > 1) {
            $appends['page'] = $page;
        }
        
        if ($limit > 10) {
            $appends['limit'] = $limit;
        }
        
        return new LengthAwarePaginator($comments, $comments_all, $limit, $page,
            ['path' => Paginator::resolveCurrentPath(), 'query' => $appends]);
    }
}

