@extends('layouts.app')

@section('content')
    <a href="{{route('admin.stores.create')}}" class="btn btn-lg btn-success">CRIAR LOJA</a>
<table class="table table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th>Loja</th>
        <th>Ações</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($stores as $store)
        <tr>
            <td>{{$store->id}}</td>
            <td>{{$store->name}}</td>
            <td>
                <a href="{{route('admin.stores.edit', ['store'=>$store->id])}}" class="btn btn-sm btn-info">EDITAR</a>
                <a href="{{route('admin.stores.destroy', ['store'=>$store->id])}}" class="btn btn-sm btn-danger">REMOVER</a>
            </td>
        </tr>
    @endforeach
    </tbody>
    <tr>
        <td></td>
    </tr>
</table>
{{$stores->links()}}
@endsection
