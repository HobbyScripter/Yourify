<?php

namespace Yourify\Traits;

use Yourify\Repository\Contracts\ICategoryRepository;
use Yourify\Repository\Contracts\ITagRepository;

trait CommonMethodsForController
{

    protected $app;

    protected $page_name;

    protected $category_name;

    protected function getMetaDefault():array{
        return array_merge([
            'og_prefix' => 'og: http://ogp.me/ns#',
            'title' => null,
            'description' => null,
            'keywords' => null,
            'url_current' => $this->app['url']->current(),
            'images' => [
                [
                    'url' => null,
                    'width' => 120,
                    'height' => 120,
                    'type' => 'image/png'
                ]
            ],
            'news' => null,
        ], trans('news::meta'));
    }
    protected function setPageName(string $page): void
    {
        $this->page_name = $page;
    }

    /**
     * @return string
     */
    protected function getPageName(): string
    {
        return $this->page_name;
    }

    /**
     * @param string|null $category
     * @return void
     */
    protected function setCurrentCategoryName(?string $category): void
    {
        $this->category_name = $category;
    }

    /**
     * @return null|string
     */
    protected function getCurrentCategoryName():?string
    {
        return $this->category_name;
    }

    /**
     * @return void
     */
    protected function prepareHeaderData(): void
    {
        view()->composer('news::common.header', function ($view) {

            $categories = $this->app->make(ICategoryRepository::class)->getActive();

            $user = $this->app['auth']->user();
            $current_category = $this->getCurrentCategoryName();

            $current_page = $this->getPageName();

            return $view->with(compact('current_category', 'categories', 'current_page', 'user'));
        });
    }

    /**
     * @param array $meta
     * @return void
     */
    protected function prepareMetaData(array $meta = []): void
    {
        view()->composer('news::common.meta', function ($view) use ($meta) {

            $meta = !empty($meta) ? array_merge($this->getMetaDefault(), $meta) : $this->getMetaDefault();

            return $view->with(compact('meta'));
        });
    }

    /**
     * @param \Illuminate\Support\Collection|array $breadcrumbs
     * @return void
     */
    protected function prepareBreadcrumbData($breadcrumbs): void
    {
        view()->composer('news::common.breadcrumb', function ($view) use ($breadcrumbs) {
            return $view->with(compact('breadcrumbs'));
        });
    }

    /**
     * @param null|string $active
     * @return void
     */
    protected function prepareTagsData(?string $active = null): void
    {
        view()->composer('news::common.tags_list', function ($view) use ($active){

            $tags = $this->app->make(ITagRepository::class)->allActiveAndNotEmpty();

            return $view->with(compact('tags', 'active'));
        });
    }
}