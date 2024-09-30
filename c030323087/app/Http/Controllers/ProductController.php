<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index():View{
        $products = Product::latest()->paginate(10);

        return view("products.index", compact("products"));
    }

    public function create():View{
        return view("products.create");
    }

    public function store(Request $request): RedirectResponse{
        $request -> validate([
            "image"=>"required|image|mimes:jpeg,jpg,png|max:2048",
            "title"=>"required"|"min:5",
            "description" => "required|min:10",
            "price" => "required:numeric",
            "stock" => "required:numeric",
        ]);

        // upload image
        $image = $request -> file("image");
        $image->storeAs("public/products", $image->hashName());

        Product::create([

            "image" => $image->hashName(),
            "title" => $request->title,
            "description" => $request->description,
            "price" => $request->price,
            "stock"=> $request->stock
        ]);

        return redirect()->route("products.index")->with(["success" => "Data berhasil disimpan"]);

    }
}
