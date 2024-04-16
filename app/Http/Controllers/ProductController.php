<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(){
        $products = Product::all();
        return response()->json($products);
    }

    public function show($id){
        $id = intval($id);
        $product = Product::where('id', $id)->first();
        if($product){
            return response()->json($product);
        }
        return response()->json(['message' => 'Product not found'], 404);
    }
    
    public function store(Request $request){
        $lastOrder = Product::orderBy('id', 'desc')->first();
        $nextOrderId = $lastOrder ? $lastOrder->id + 1 : 1;
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|alpha_num|unique:products',
            'description' => 'required|string|max:50',
            'price' => 'required|numeric|min:0.01',
            'img' => 'required|mimes:jpeg,png,jpg|max:2048',
        ])->withMessages([
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede exceder los 50 caracteres.',
            'name.alpha_num' => 'El nombre solo puede contener letras y números.',
            'name.unique' => 'El nombre del producto ya está en uso.',
        
            'description.required' => 'La descripción es obligatoria.',
            'description.string' => 'La descripción debe ser una cadena de texto.',
            'description.max' => 'La descripción no puede exceder los 50 caracteres.',
        
            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un valor numérico.',
            'price.min' => 'El precio no puede ser inferior a 0.01.',
        
            'img.required' => 'La imagen es obligatoria.',
            'img.mimes' => 'El archivo debe ser una imagen (jpeg, png, jpg).',
            'img.max' => 'La imagen no puede pesar más de 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file = $request->file('img');
        $path = Storage::disk('s3')->put('products', $file);
        $url = Storage::disk('s3')->url($path); //Es normal el error, relaja las tetas Isaac

        $product = new Product();
        $product->description = $request->description;
        $product->id = $nextOrderId;
        $product->name = $request->name;
        $product->img = $url;
        $product->price = $request->price;
        $product->save();
        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }

    

    public function update(Request $request, $id){
        $id = intval($id);
        $product = Product::where('id', $id)->first();
        if($product){
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:50|alpha_num|unique:products',
                'description' => 'required|string|max:50',
                'price' => 'required|numeric|min:0.01',
                'img' => 'mimes:jpeg,png,jpg|max:2048',
            ])->withMessages([
                'name.required' => 'El campo nombre es obligatorio.',
                'name.string' => 'El nombre debe ser una cadena de texto.',
                'name.max' => 'El nombre no puede exceder los 50 caracteres.',
                'name.alpha_num' => 'El nombre solo puede contener letras y números.',
                'name.unique' => 'El nombre del producto ya está en uso.',
            
                'description.required' => 'La descripción es obligatoria.',
                'description.string' => 'La descripción debe ser una cadena de texto.',
                'description.max' => 'La descripción no puede exceder los 50 caracteres.',
            
                'price.required' => 'El precio es obligatorio.',
                'price.numeric' => 'El precio debe ser un valor numérico.',
                'price.min' => 'El precio no puede ser inferior a 0.01.',
            
                
                'img.mimes' => 'El archivo debe ser una imagen (jpeg, png, jpg).',
                'img.max' => 'La imagen no puede pesar más de 2MB.',
            ]);
    

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $product->description = $request->description;
            $product->name = $request->name;
            if ($request->hasFile('img')) {
                $file = $request->file('img');
                $path = Storage::disk('s3')->put('products', $file);
                $url = Storage::disk('s3')->url($path);
                $product->img->$url;
            }
            $product->price = $request->price;
            $product->save();
            return response()->json([
                'message' => 'Product updated successfully',
                'product' => $product
            ]);
        }
        return response()->json(['message' => 'Product not found'], 404);
    }

    public function destroy($id){
        $id = intval($id);
        $product = Product::where('id', $id)->first();
        if($product){
            $product->delete();
            return response()->json(['message' => 'Product deleted successfully'], 200);
        }
        return response()->json(['message' => 'Product not found'], 404);
    }
}
