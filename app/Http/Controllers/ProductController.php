<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Product;  
use Illuminate\Support\Facades\Validator;
use DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $response = [
        'status' => 'success',
        'message' => 'Successful',
        'products' => Product::where('user_id',auth()->user()->id)->get(),
        ];
        return response($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
            'des' => 'required',
            'product_image' => 'required',
            'category' => 'required',
            'product_image.*' => 'image|mimes:jpeg,png,jpg,gif|max:20048',
        ]);

        if ($validator->fails()) {
                  $response = [
        'status' => 'error',
        'message' => $validator->errors(),
      ];
    return response($response, 200);
                }
        
             

    if($request->hasFile('product_image')){
      $file = $request->product_image;
      $filename = time().'.'.$file->getClientOriginalExtension();
      $file->move(public_path('images/product/'.auth()->user()->id.'/'), $filename);
      $path = url('/').'/public/images/product/'.auth()->user()->id.'/'.$filename;
     }
        $product = Product::create([
         'user_id' => auth()->user()->id,
         'name'    => $request->name ?? '',
         'price'   => $request->price ?? 0.0,
         'des'     => $request->des ?? '',
         'category' => $request->category,
         'status'  => 1,
         'images'  => $path, 
         'url'     => $request->url,
        ]);
        if($product){
        $response = [
        'status' => 'success',
        'message' => 'Product Created'
        ];
        return response($response, 200);
      }else{
        return response(['status' => 'error', 'message' => 'Database error'], 200);
      }
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
        $valid = Product::find($id);
        if($valid){
        $response = [
        'status' => 'success',
        'message' => 'successful',
        'product' => Product::find($id),
        ];
        return response($response, 200);
     }else{
        return response(['status' => 'error','message' => 'Data not found'], 200);
     }
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
       
        $product = Product::find($id);
        if($product){
          
            
      if($request->hasFile('product_image')){
      $file = $request->product_image;
      $filename = time().'.'.$file->getClientOriginalExtension();
      $file->move(public_path('images/product/'.auth()->user()->id.'/'), $filename);
      $path = url('/').'/public/images/product/'.auth()->user()->id.'/'.$filename;
       //delet existing image from path
          
                $dfile = url('public/images/product/'.auth()->user()->id).'/'.$product->images;
                if (file_exists($dfile)) {
                    unlink($dfile);
                    } 
             
             $data['images'] = $path;
     }
      
     
      
     
        //     
       $data = [
         'name'    => $request->name ?? '',
         'price'   => $request->price ?? 0.0,
         'des'     => $request->des ?? '',
         'category' => $request->category ?? '',
         'url'     => $request->url ?? '',
         ];
        $product->update($data);
        $response = [
        'status' => 'success',
        'message' => 'Product Update successful',
         ];
        return response($response, 200);
       }else{
        return response(['status' => 'error', 'message' => 'Product not found in dataset'], 200);
       }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        Product::destroy($id);
        $response = [
        'status' => 'success',
        'message' => 'Deleted',
        ];
        return response($response, 200);
    }

    /**
     * Search for product.
     *
     * @param  str  $name
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        //
        $response = [
        'status' => 'success',
        'message' => 'Successful',
        'products' => Product::where('name', 'like', '%'.$name.'%')->get(),
        ];
        return response($response, 200);
         
    }
    
    //Categories
    
    public function getCategory()
    {

        $response = [
        'status' => 'success',
        'message' => 'Successful',
        'categories' => DB::table('category')->where('user_id',auth()->user()->id)->get(),
        ];
        return response($response, 200);
    }
    
    public function createCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
          'name' => 'required|string',
         ]);
         if ($validator->fails()) {
        $response = [
        'status' => 'error',
        'message' => $validator->errors(),
      ];
    return response($response, 200);
                }
    $userId = auth()->user()->id;            
      DB::table('category')->insert([
               'user_id'=>  $userId,
               'name' => $request->name ?? '',
              ]); 
       $response = [
        'status' => 'success',
        'message' => 'Category created Successfuly',
      ];
      return response($response, 200);
    }
    
    public function editCategory($id)
    {
        $userId = auth()->user()->id;
    $cat = DB::table('category')->where('id',$id)->where('user_id', $userId)->first();
    if(!empty($cat)){
        
        $response = [
        'status' => 'success',
        'message' => 'successful',
         'category' => $cat,
         
         ];
        return response($response, 200);
    }else{
        $response = [
        'status' => 'error',
        'message' => 'Data not found',
         ];
        return response($response, 200);
    }
    }
    
    public function updateCategory(Request $request, $id)
    {
      $validator = Validator::make($request->all(), [
          'name' => 'required',
         ]);
         if ($validator->fails()) {
        $response = [
        'status' => 'error',
        'message' => $validator->errors(),
      ];
    return response($response, 200);
                }
    $userId = auth()->user()->id;            
      DB::table('category')->where('id',$id)->update([
               'name' => $request->name ?? '',
              ]); 
       $response = [
        'status' => 'success',
        'message' => 'category update Successfuly',
      ]; 
      return response($response, 200);
    }
    
     public function deleteCategory($id)
    {

      if(DB::table('category')->where('id', $id)->delete()){
        $response = [
        'status' => 'success',
        'message' => 'deleted',
        ];
        return response($response, 200);
      }else{
        $response = [
        'status' => 'error',
        'message' => 'Do not exist',
        ];
        return response($response, 200);
      }
    }
    
    public function searchCategory($name)
    {
        //
        $response = [
        'status' => 'success',
        'message' => 'Successful',
        'categories' => DB::table('category')->where('name', 'like', '%'.$name.'%')->get(),
        ];
        return response($response, 200);
         
    }
    
    
    
}
