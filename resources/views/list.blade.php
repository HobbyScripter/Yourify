@extends('layouts.app')
@section('content')
        <table class="table table-responsive">
            <thead>
                <tr>
                    <th>{{trans('start.list.labels.id')}}</th>
                    <th>{{trans('start.list.labels.name')}}</th>
                    <th>{{trans('start.list.labels.mail')}}</th>
                </tr>
            </thead>
            @foreach($user as $key => $value)
                <tr>
                    <td>{{$value->id}}</td>
                    <td>{{$value->name}}</td>
                    <td>{{$value->email}}</td>
                </tr>
            @endforeach
        </table>
@endsection