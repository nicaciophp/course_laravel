@extends('layouts.app')

@section('content')
    @if(!$store)
    <a href="{{route('admin.stores.create')}}" class="btn btn-lg btn-success">CRIAR LOJA</a>
    @endif
<table class="table table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th>Loja</th>
        <th>Total de Produtos</th>
        <th>Ações</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{$store->id}}</td>
            <td>{{$store->name}}</td>
            <td>{{$store->products->count()}}</td>
            <td>
                <a href="{{route('admin.stores.edit', ['store'=>$store->id])}}" class="btn btn-sm btn-info">EDITAR</a>
                <div class="btn-group">
                    <form action="{{route('admin.stores.destroy', ['store'=>$store->id])}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">REMOVER</button>
                    </form>
                </div>
            </td>
        </tr>
    </tbody>
</table>
@endsection
