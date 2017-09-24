@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">News</div>

                <div class="panel-body">
                    @foreach($news as $p)
                        <blockquote>
                            <div>{{$p->title}}</div>
                            <div>{{$p->desc}}</div>
                            <div><a href="{{ url('profile/'.$p->author_id) }}">{{$p->user->name}}</a></div>
                        </blockquote>
                        <hr/>
                    @endforeach
                    @if($users)
                       @foreach($users as $u)
                           @if($u->isOnline())
                               <li>{{$u->name}}</li>
                           @endif
                       @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
