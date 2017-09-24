@extends('layouts.app')
@section('content')
    @foreach($news as $p)
    <blockquote>
        <div>{{$p->title}}</div>
        <div>{{$p->desc}}</div>
        <div><a href="{{ url('profile/'.$p->author_id) }}">{{$p->user->name}}</a></div>
    </blockquote>
    @endforeach
@endsection