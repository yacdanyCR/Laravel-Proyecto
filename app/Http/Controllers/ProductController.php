<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class ProductController extends Controller
{
    //muestra todos los productos en pagina de inicio
    public function index(){
        $products=Product::paginate(8);
        $category=Category::all();
        return view('welcome',compact('products','category'));
    }

    //mostrar producto seleccionado en pagina de inicio
    public function show($id){
       $products=Product::find($id); 
      return view('productShow',compact('products'));  
    }

    //buscar productos
    public function search(Request $request){
        $products=Product::where('nombre','LIKE','%'.$request->search.'%')->get();
        return view('productSearch',compact('products'));
    }

    //productos por categoria
    public function categoria(Request $request){
        $category=Category::all();
        $products=DB::table('products')
                    ->join('categories','products.category_id','=','categories.id')
                    ->where('products.category_id','=',$request->id)
                    ->select('products.*')
                    ->get();
        return view('productCategoria',compact('products','category'));
    }

    //comentar publicaciones de los usuarios
    public function addComent(Request $request){
        $coment=new Coment();
        $coment->product_id=$request->product_id;
        $coment->user_id=Auth::id();
        $coment->message=$request->coment;

        $coment->save();
        return redirect()->route('product.show',['id'=>$request->product_id])->with('success','Comentario Añadido');
    }
}
