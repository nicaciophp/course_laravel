<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use App\Traits\UploadTrait;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    use UploadTrait;
    private $product;
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user->store()->exists()){
            flash('É preciso criar uma loja para cadastrar produtos')->warning();
            return redirect()->route('admin.stores.index');
        }

        $products = $user->store->products()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = \App\Category::all();
//        dd($categories);

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $data = $request->all();
        $categories = $request->get('categories', null);

        $data['price'] = formatPriceToDatabase($data['price']);
        
        $store = auth()->user()->store;
//        $store->products()->create($data);

        $product = $store->products()->create($data);
        $product->categories()->sync($categories);

        if ($request->hasFile('photos')){
            $images = $this->imageUpload($request->file('photos'), 'image');
            //inserção destas imagens na base
            $product->photos()->createMany($images);
        }

        flash('Product Criado com Sucesso')->success();

        return redirect()->route('admin.products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($product)
    {
        $product = $this->product->findOrFail($product);
        $categories = \App\Category::all(['id', 'name']);

        return view('admin.products.edit', compact('product','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $product)
    {
        $data = $request->all();
        $categories = $request->get('categories', null);

        $product = $this->product->find($product);
        $product->update($data);
        if (!is_null($categories)) {
            $product->categories()->sync($categories);
        }
        if ($request->hasFile('photos')){
            $images = $this->imageUpload($request->file('photos'), 'image');
            //inserção destas imagens na base
            $product->photos()->createMany($images);
        }

        flash('Product Atualizado com Sucesso')->success();

        return redirect()->route('admin.products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($product)
    {
//        $data = $request->all();
        $product = $this->product->find($product);
        $product->delete();

        flash('Product Removido com Sucesso')->success();

        return redirect()->route('admin.products.index');
    }

}
