@extends('layouts.app')

@section('content')
    <h1>Criar Loja</h1>
    <form action="{{route('admin.stores.update', ['store'=>$store->id])}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="">Nome Loja</label>
            <input type="text" name="name" class="form-control" value="{{$store->name}}">

        </div>
        <div class="form-group">
            <label for="">Descrição</label>
            <input type="text" name="description" class="form-control" value="{{$store->description}}">

        </div>
        <div class="form-group">
            <label for="">Telefone</label>
            <input type="text" name="phone" class="form-control" value="{{$store->phone}}">

        </div>
        <div class="form-group">
            <label for="">Celular / Whatsapp</label>
            <input type="text" name="mobile_phone" class="form-control" value="{{$store->mobile_phone}}">

        </div>
        <div class="form-group">
            <p>
                <img src="{{asset('storage/'.$store->logo)}}" alt="">
            </p>
            <label for="">Logo Loja</label>
            <input type="file" class="form-control  @error('logo') is-invalid @enderror" name="logo" multiple>
            @error('logo')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror
        </div>
        <div>
            <button type="submit" class="btn btn-lg btn-success">Atualizar Loja</button>
        </div>
    </form>

@endsection
