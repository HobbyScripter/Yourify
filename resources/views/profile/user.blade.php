@extends('layouts.app')
@section('content')
    <p>Welcome to your profile page {{$user->name}}</p><br>
    <p> {{$user->email}}</p>
@endsection