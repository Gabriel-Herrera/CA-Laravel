<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Búsqueda por nombre
        if ($request->has('search')) {
            $query->where('nombre', 'like', '%' . $request->input('search') . '%');
        }

        // Filtro por categoría
        if ($request->has('category') && $request->input('category') !== '') {
            $query->where('categoria_id', $request->input('category'));
        }

        $products = $query->paginate(12);
        $categories = Category::all();
        $featuredProducts = Product::inRandomOrder()->take(5)->get();

        return view('products.index', compact('products', 'categories', 'featuredProducts'));
    }

    public function adminIndex()
{
    $products = Product::paginate(10);
    return view('admin.products.index', compact('products'));
}

public function show($id)
{
    $product = Product::findOrFail($id);
    $relatedProducts = Product::where('categoria_id', $product->categoria_id)
                              ->where('id', '!=', $product->id)
                              ->take(4)
                              ->get();

    return view('products.show', compact('product', 'relatedProducts'));
}

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function category($category)
    {
        $products = Product::where('categoria', $category)->paginate(12);
        return view('products.category', compact('products', 'category'));
    }

    public function storeProduct(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'required',
            'precio' => 'required|numeric|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'stock' => 'required|integer|min:0',
            'imagen' => 'image|mimes:jpeg,png,jpg,gif,web|max:2048'
        ]);

        if ($request->hasFile('imagen')) {
            $imagePath = $request->file('imagen')->store('productos', 'public');
            $validatedData['imagen'] = $imagePath;
        }

        Product::create($validatedData);

        return redirect()->route('admin.products.index')->with('success', 'Producto creado exitosamente.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'required',
            'precio' => 'required|numeric|min:0',
            'categoria' => 'required|exists:categorias,nombre',
            'stock' => 'required|integer|min:0',
            'imagen' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('imagen')) {
            if ($product->imagen) {
                Storage::disk('public')->delete($product->imagen);
            }
            $imagePath = $request->file('imagen')->store('productos', 'public');
            $validatedData['imagen'] = $imagePath;
        }

        $product->update($validatedData);

        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Product $product)
    {
        if ($product->imagen) {
            Storage::disk('public')->delete($product->imagen);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Producto eliminado exitosamente.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('nombre', 'like', "%$query%")
                           ->orWhere('descripcion', 'like', "%$query%")
                           ->paginate(10);
        return view('admin.products.index', compact('products'));
    }
}
