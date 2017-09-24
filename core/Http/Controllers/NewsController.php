<?php

namespace Yourify\Http\Controllers;

use Yourify\Models\News;


class NewsController extends Controller
{

    protected $news;

    public function __construct( News $news)
    {
        $this->news = $news;
    }


    public function index(){
        return view('news.index', ['news' => News::all()]);
    }

    public function show(){
        $news = News::all();
        return view('news.show',compact('news'));
    }
}
