@extends('layouts.app')

@section('content')
    <h1>Atualizar Produto</h1>
    <form action="{{route('admin.products.update', ['product'=> $product->id])}}" method="post"
          enctype="multipart/form-data">
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
                    <option value="{{$category->id}}"
                            @if($product->categories->contains($category)) selected @endif>{{$category->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="">Fotos do Produto</label>
            <input type="file" class="form-control @error('photos.*') is-invalid @enderror" name="photos[]" multiple>
            @error('photos.*')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror
        </div>

        <div>
            <button type="submit" class="btn btn-lg btn-success">Atualizar Produto</button>
        </div>
    </form>
    <hr>
    <div class="row">
        @foreach($product->photos as $photo)
            <div class="col-4 text-center">
                <img src="{{asset('storage/'.$photo->image)}}" alt="" class="img-fluid">
                <form action="{{route('admin.photo.remove', ['photoName'=>$photo->image])}}" method="post">
                    @csrf
                    <input type="hidden" name="photoName" value="{{$photo->image}}">
                    <button type="submit" class="btn btn-lg btn-danger">REMOVER</button>
                </form>
            </div>
        @endforeach
    </div>
@endsection
