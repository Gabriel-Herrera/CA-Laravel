<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = session()->get('cart', []);
        $total = 0;
        $products = [];

        foreach ($cartItems as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $products[] = [
                    'product' => $product,
                    'quantity' => $details['quantity']
                ];
                $total += $product->precio * $details['quantity'];
            }
        }

        $appliedDiscount = session()->get('applied_discount');

        if ($appliedDiscount) {
            $total -= $appliedDiscount['saved_amount'];
        }

        return view('cart.index', compact('products', 'total', 'appliedDiscount'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:productos,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::find($request->product_id);

        if (!$product || $product->stock < $request->quantity) {
            return back()->with('error', 'Producto no disponible en la cantidad solicitada.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity'] += $request->quantity;
        } else {
            $cart[$request->product_id] = [
                'quantity' => $request->quantity
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Producto agregado al carrito.');
    }

    // In app/Http/Controllers/CartController.php

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = (int)$request->quantity;
            session()->put('cart', $cart);

            // Calcular nuevos totales
            $product = Product::find($id);
            $item_total = $product->precio * $cart[$id]['quantity'];
            $cart_total = 0;
            $cart_count = 0;

            foreach ($cart as $item_id => $details) {
                $p = Product::find($item_id);
                $cart_total += $p->precio * $details['quantity'];
                $cart_count += $details['quantity'];
            }

            return response()->json([
                'success' => true,
                'item_total' => number_format($item_total, 0, ',', '.'),
                'cart_total' => number_format($cart_total, 0, ',', '.'),
                'cart_count' => $cart_count
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Producto no encontrado'
        ], 404);
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);

            // Calcular nuevo total del carrito
            $cart_total = 0;
            $cart_count = 0;
            foreach ($cart as $item_id => $details) {
                $product = Product::find($item_id);
                $cart_total += $product->precio * $details['quantity'];
                $cart_count += $details['quantity'];
            }

            return response()->json([
                'success' => true,
                'cart_total' => number_format($cart_total, 0, ',', '.'),
                'cart_count' => $cart_count
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Producto no encontrado'
        ], 404);
    }

    public function applyDiscount(Request $request)
    {
        $request->validate([
            'discount_code' => 'required|string',
        ]);

        $discount = Discount::where('code', $request->discount_code)->first();

        if (!$discount || !$discount->isValid()) {
            return back()->with('error', 'Invalid discount code.');
        }

        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            $total += $product->precio * $details['quantity'];
        }

        $discountedTotal = $discount->apply($total);
        $savedAmount = $total - $discountedTotal;

        session()->put('applied_discount', [
            'code' => $discount->code,
            'saved_amount' => $savedAmount,
        ]);

        $discount->increment('uses');

        return back()->with('success', 'Discount applied successfully.');
    }

}
