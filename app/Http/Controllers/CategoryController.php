<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;

class CategoryController extends Controller
{
    public function categoryIndex()
    {
        // Aseguramos que todas las variables estén definidas usando el modelo Category
        $totalProducts = Product::count();
        $totalCategories = Category::count(); // Asegúrate de que el modelo Category esté importado
        $totalOrders = Order::count();
        $latestProducts = Product::latest()->take(5)->get();

        // Verificamos que el modelo Category exista y funcione correctamente
        try {
            $totalCategories = Category::count();
        } catch (\Exception $e) {
            $totalCategories = 0; // Valor por defecto si hay algún error
        }

        return view('admin.categories.index', compact(
            'totalProducts',
            'totalCategories',
            'totalOrders',
            'latestProducts'
        ));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|unique:categorias,nombre|max:255',
            'descripcion' => 'nullable'
        ]);

        Category::create($validatedData);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría creada exitosamente.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre,'.$category->id,
            'descripcion' => 'nullable|string'
        ]);

        $category->update($validatedData);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría actualizada correctamente');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría eliminada correctamente');
    }
}
