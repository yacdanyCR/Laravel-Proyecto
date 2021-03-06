<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ProductPostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //DEVOLVER LOS PRODUCTOS QUE CORRESPONDEN AL USUARIO DE LA SESSION
        $user=Auth::user();
        $products = Product::with('category')
            ->join('users', 'products.user_id', '=', 'users.id')
            ->where('products.user_id', '=', $user->id)
            ->select('products.*')
            ->get();

        return view('dashboard', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /* Pagina para crear nuevos productos*/
    public function create()
    {
        $category=Category::all();
        return view('addProduct',compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $product=new Product();

        if ($request->hasFile('imagen')) {
            $photo = $request->file('imagen');
            $photo_name = time() . $photo->getClientOriginalName();
            $photo->move('storage/img/posts/', $photo_name);
            $product->imagen = $photo_name;
        }

        $product->user_id=Auth::id();
        $product->nombre = $request->nombre;
        $product->category_id = $request->categoria;
      
        $product->save();

        return redirect()->route('dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $products = Product::findOrfail($id);
        $category=Category::all();
        return view('updateProduct', compact('products','category'));
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

        if ($request->hasFile('imagen')) {
            $photo = $request->file('imagen');
            $photo_name = time() . $photo->getClientOriginalName();
            $photo->move('storage/img/posts/', $photo_name);
            $product->imagen = $photo_name;
        }

        $product->nombre = $request->nombre;
        $product->category_id = $request->categoria;
      
        $product->save();
        return redirect()->route('dashboard')->with('success','iamgen creada');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('dashboard');
    }
}
