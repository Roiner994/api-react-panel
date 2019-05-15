<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        $response = ['success'=>true, 'data'=>$categories];
        return response()->json($response, 201);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $category = new Category();
        $category->fill($request->all());
        $category->save();
        $response = $category ?
                    $response = ['success'=>true, 'data'=>$category->toArray()]:
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
    public function update(CategoryRequest $request, $id)
    {
        $category = Category::find($id);
        
        if ($category) {
            $category->fill($request->all());
            $category->save();
            $response = ['success'=>true, 'data'=>$category->toArray()];
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
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            $response = ['success'=>true, 'data'=>$category->toArray()];
        }else{
            $response = ['success'=>false, 'data'=>'Error al eliminar usuario'];
        }

        return response()->json($response, 201);
    }
}
