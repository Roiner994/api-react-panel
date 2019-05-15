<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        $products = Product::getCategories($products);
        $response = ['success'=>true, 'data'=>$products];
        return response()->json($response, 201);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        
        $product = new Product();
        $product->fill($request->only('name','cant','price','category_id'));

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = time().$file->getClientOriginalName();
            $file->move(public_path().'/images/products',$name);   
            $product->image = $name;
        }

        $product->save();

        $response = $product ?
                    $response = ['success'=>true, 'data'=>$product->toArray()]:
                    $response = ['success'=>false, 'data'=>'Error al agregar la categoria'];
        return response()->json($response, 201);
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $product = Product::find($id);
        
        if ($product) {
            $product->fill($request->only('name','cant','price','category_id'));

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $name = time().$file->getClientOriginalName();
                $file->move(public_path().'/images/products',$name);   
                $product->image = $name;
            }

            $product->save();

            $response = ['success'=>true, 'data'=>$product->toArray()];
        }else{
            $response = ['success'=>false, 'data'=>'Categoria no encontrada'];
        }
        return response()->json($response, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            $response = ['success'=>true, 'data'=>$product->toArray()];
        }else{
            $response = ['success'=>false, 'data'=>'Error al eliminar usuario'];
        }

        return response()->json($response, 201);
    }
}
