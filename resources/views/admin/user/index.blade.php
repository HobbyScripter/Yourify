@extends('layouts._dash')
@section('main_content')
    <table class="table table-responsive">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>E-mail</th>
            <th>Gruppen</th>
            <th>Aktionen</th>
        </tr>
        </thead>
        @foreach($user as $key => $value)
            <tr>
                <td>{{$value->id}}</td>
                <td>{{$value->name}}</td>
                <td>{{$value->email}}</td>
                <td>
                    @if(!empty($value->roles))
                        @foreach($value->roles as $k)
                            <label class="label label-success">{{ $k->display_name }}</label>
                        @endforeach
                    @endif
                </td>
                <td>
                    <a class="btn btn-info" href="{{ route('users.show', $value->id) }}">Show</a>
                    <a class="btn btn-primary" href="{{ route('users.edit', $value->id) }}">Edit</a>
                    {!! Form::open(['method' => 'DELETE','route' => ['users.destroy',$value->id],'style' => 'display:inline']) !!}
                    {!! Form::submit('Delete',['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
    </table>
@endsection