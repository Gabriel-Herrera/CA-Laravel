<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Charge;

class CheckoutController extends Controller
{
    const SHIPPING_COST = 3000; // 3000 CLP
    const FREE_SHIPPING_THRESHOLD = 20000; // 20000 CLP

    public function show()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        $items = [];

        foreach ($cart as $id => $details) {
            $product = \App\Models\Product::find($id);
            if ($product) {
                $price = $product->precio * (1 - ($product->descuento / 100));
                $subtotal = $price * $details['quantity'];
                $total += $subtotal;
                $items[] = [
                    'product' => $product,
                    'quantity' => $details['quantity'],
                    'subtotal' => $subtotal
                ];
            }
        }

        $shipping = $total >= self::FREE_SHIPPING_THRESHOLD ? 0 : self::SHIPPING_COST;
        $total += $shipping;

        return view('checkout.show', compact('items', 'total', 'shipping'));
    }

    public function process(Request $request)
    {
        try {
            DB::beginTransaction();

            $cart = session()->get('cart', []);
            if (empty($cart)) {
                return redirect()->back()->with('error', 'El carrito está vacío');
            }

            $total = 0;
            foreach ($cart as $id => $details) {
                $product = \App\Models\Product::find($id);
                if ($product) {
                    $price = $product->precio * (1 - ($product->descuento / 100));
                    $total += $price * $details['quantity'];
                }
            }

            $shipping = $total >= self::FREE_SHIPPING_THRESHOLD ? 0 : self::SHIPPING_COST;
            $total += $shipping;

            // Procesar el pago con Stripe
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $charge = Charge::create([
                'amount' => $total * 100, // Stripe espera el monto en centavos
                'currency' => 'clp',
                'source' => $request->stripeToken,
                'description' => 'Compra en Compra Ágil',
            ]);

            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $total,
                'estado' => 'pagado',
                'stripe_id' => $charge->id,
            ]);

            foreach ($cart as $id => $details) {
                $product = \App\Models\Product::find($id);
                if ($product) {
                    OrderDetail::create([
                        'pedido_id' => $order->id,
                        'producto_id' => $id,
                        'cantidad' => $details['quantity'],
                        'precio_unitario' => $product->precio * (1 - ($product->descuento / 100))
                    ]);

                    // Actualizar stock
                    $product->stock -= $details['quantity'];
                    $product->save();
                }
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('orders.show', $order->id)->with('success', 'Pedido realizado y pagado correctamente');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }
}
