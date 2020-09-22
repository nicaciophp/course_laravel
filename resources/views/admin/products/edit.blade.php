@extends('layouts.app')

@section('content')
    <h1>Atualizar Produto</h1>
    <form action="{{route('admin.products.update', ['product'=> $product->id])}}" method="post">
        <input type="hidden" name="_method" value="PUT">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="">Nome Produto</label>
            <input type="text" name="name" class="form-control" value="{{$product->name}}">

        </div>
        <div class="form-group">
            <label for="">Descrição</label>
            <input type="text" name="description" class="form-control" value="{{$product->description}}">

        </div>
        <div class="form-group">
            <label for="">Conteúdo</label>
            <textarea name="body" id="" cols="30" rows="10" class="form-control">{{$product->body}}</textarea>
        </div>

        <div class="form-group">
            <label for="">Preço</label>
            <input type="text" name="price" class="form-control" value="{{$product->price}}">

        </div>
        <div class="form-group">
            <label for="">Categorias</label>
            <select name="categories[]" id="" multiple class="form-control">
                @foreach($categories as $category)
                    <option value="{{$category->id}}" @if($product->categories->contains($category)) selected @endif>{{$category->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="">Slug</label>
            <input type="text" name="slug" class="form-control" value="{{$product->slug}}">

        </div>
        <div>
            <button type="submit" class="btn btn-lg btn-success">Atualizar Produto</button>
        </div>
    </form>

@endsection
