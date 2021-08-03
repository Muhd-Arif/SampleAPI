<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'price' => 'required',
        ]);

        return Product::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Product::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->update($request->all());

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Product::destroy($id);
    }

    /**
     * Search for a name
     *
     * @param  str  $name
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        return Product::where('name', 'like', '%' . $name . '%')->get();
    }

    public function oneArray()
    {   
        $data = Product::all();
        
        return response([
            'data' => $data->pluck('price')->implode(', '),
        ]);
    }

    public function twoArray()
    {   
        // return response([
        //     'data' => Product::get(['name', 'price'])->toArray(),
        // ]);

        // $products = collect(Product::all())->map(function($product){
        //     return $newArray[] = [ $data->created_at->format('d/m/Y'), $data->name ];
        // });

        $products = collect(Product::all())->map(function($product){
            return $newArray[] = [$product->price, $product->name];
        });

        // dd($products);
        
        return response([
            'data' => $products,
        ]);
    }
}