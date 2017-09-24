@extends('layouts.app')
@section('content')
    @if(Auth::check())
        <p>Welcome to your profile page {{Auth::user()->name}}</p><br>
        <p> {{Auth::user()->email}}</p>
    @endif
@endsection