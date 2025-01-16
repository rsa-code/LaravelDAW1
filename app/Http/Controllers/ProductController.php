<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(){
		$data['products'] = Product::orderBy('price','desc')->paginate(5);
		return response(view('product.list',$data));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response(view('product.create'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $request->validate([
 'title' => 'required|regex:/^[A-z\s]+$/|max:150|unique:products',
 'code' => 'required|regex:/^[A-z0-9\-\_]+$/|max:50|unique:products',
 'description' => 'required|regex:/^[\p{L}0-9-.,\s]+$/u|max:1500',
 'price' => 'required|numeric|between:0,500',
 'image' => 'nullable|sometimes|image|mimes:jpeg,png,jpg',
 ]);
 //deal with the image, if any
 if( array_key_exists('image', $result) ){
//there is an image to insert
if( $request->file('image')->isValid() ){
$filename = time() . "_" . $request->file('image')->getClientOriginalName();
$result = $request->file('image')->move('storage/', $filename);
}
else{
//error - return message
return Redirect::to('products.create')->with('error','Something went wrong with the file: please
try again later.');
}
 }
 //get data to insert into the table
 $data = $request->all();
 //remove token, if it exists
 if( array_key_exists('_token', $data) ){
 unset($data['_token']);
 }

 if ( !isset($filename) ){
//there is no image to insert so use the default one
$data['image'] = "defaultImage.jpg";
 }
 else{
$data['image'] = $filename;
 }
 //insert new product
 Product::create($data);

 return redirect('products')->with('success','Product Inserted!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $product = Product::find($id);
 //check if ‘id’ exists, as it is passed by get
	if ( empty($product) ){
		return Redirect::to('products')->with('error','Invalid operation selected.');
 }
		return view('product.edit', ['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //validate fields. In this case, only price and image (may or may not be changed)
 $receivedProductData = $request->validate([
 'price' => 'required|numeric|between:0,500',
 'image' => 'nullable|sometimes|image|mimes:jpeg,png,jpg',
 ]);
 $originalProduct = Product::find($id);
 if ( !empty($originalProduct) ){

$originalProduct['price'] = $receivedProductData['price'];

if( array_key_exists('image', $receivedProductData) ){

if( $request->file('image')->isValid() ){
$filename = time() . "_" . $request->file('image')->getClientOriginalName();
$result = $request->file('image')->move('storage/', $filename);

if ( $originalProduct['image'] != 'defaultImage.jpg'){
unlink('storage/' . $originalProduct['image']);
}
$originalProduct['image'] = $filename;
}
else{

return redirect('products')->with('error','Something went wrong with the file: please
try again later.');
}
}

$originalProduct->save();
return redirect('products')->with('success','Product data updated!');
 }
 else{

return redirect('products')->with('error','Invalid operation detected.');
 }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $product = Product::find($id);
	if ( !empty($product) && $product['image'] != 'defaultImage.jpg' && file_exists('storage/'. $product['image']) ){
		unlink('storage/' . $product['image'] );
			Product::where('id',$id)->delete();
		return redirect('products')->with('success','Product removed from list.');
 }
	elseif ( !empty($product) ){
			Product::where('id',$id)->delete();
		return redirect('products')->with('success','Product removed from list.');
 }
	else{
		return redirect('products')->with('error','Invalid operation detected.');
 }
    }
}
