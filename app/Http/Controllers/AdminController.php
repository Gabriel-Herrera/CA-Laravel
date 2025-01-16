<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {           // Aseguramos que todas las variables estén definidas usando el modelo Category
        $totalCategories = Category::count(); // Asegúrate de que el modelo Category esté importado
        $totalOrders = Order::count();
        $latestProducts = Product::latest()->take(5)->get();
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock', '<', 10)->get();
        $allProducts = Product::all();

        // Verificamos que el modelo Category exista y funcione correctamente
        try {
            $totalCategories = Category::count();
        } catch (\Exception $e) {
            $totalCategories = 0; // Valor por defecto si hay algún error
        }

        return view('admin.dashboard', compact('totalProducts', 'lowStockProducts', 'allProducts','totalCategories',
            'totalOrders',
            'latestProducts'));
    }

    public function createProduct()
    {
        return view('admin.products.create');
    }

    public function storeProduct(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'required',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categoria' => 'required',
            'stock' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('imagen')) {
            $imagePath = $request->file('imagen')->store('productos', 'public');
            $validatedData['imagen'] = $imagePath;
        }

        Product::create($validatedData);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Producto creado exitosamente.');
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validatedData = $request->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'required',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categoria' => 'required',
            'stock' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('imagen')) {
            // Eliminar la imagen anterior
            if ($product->imagen) {
                Storage::disk('public')->delete($product->imagen);
            }
            $imagePath = $request->file('imagen')->store('productos', 'public');
            $validatedData['imagen'] = $imagePath;
        }

        $product->update($validatedData);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Producto actualizado exitosamente.');
    }

}
