<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function add(Request $request, $id)
    {
        $rules = [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ];

        $product = Product::find($request->product_id);
        if ($product->variations()->exists()) {
            $rules['variation_id'] = 'required|exists:variations,id,product_id,' . $request->product_id;
        }

        $request->validate($rules);

        $product = Product::findOrFail($id);
        $variationId = $request->input('variation_id');
        $quantity = $request->input('quantity', 1);

        if ($variationId) {
            $stock = Stock::where('variation_id', $variationId)->first();
        } else {
            $stock = Stock::where('product_id', $product->id)->where('variation_id', null)->first();
        }

        if (empty($stock) || $stock->quantity < $quantity) {
            return back()->with('error', 'Produto com estoque insuficiente!');
        }

        $cart = Session::get('montinks_cart', []);
        if ($variationId) {
            $key = $id . '-' . $variationId;
            $variation = Variation::find($variationId);
            $variationName = $variation->name . ': ' . $variation->price;
        } else {
            $key = $id;
            $variationName = null;
        }

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                "product_id" => $id,
                "variation_id" => $variationId,
                "name" => $product->name,
                "variation_name" => $variationName,
                "price" => $product->price,
                "quantity" => $quantity
            ];
        }

        Session::put('montinks_cart', $cart);
        return back()->with('success', 'Produto adicionado ao carrinho!');
    }

    public function destroy($key)
    {
        $cart = Session::get('montinks_cart');
        if (isset($cart[$key])) {
            unset($cart[$key]);
            Session::put('montinks_cart', $cart);
        }
        return back()->with('success', 'Produto removido do carrinho!');
    }

    public function update(Request $request)
    {
        $cart = Session::get('montinks_cart');
        foreach ($request->quantity as $key => $quantity) {
            if (isset($cart[$key])) {

                $item = $cart[$key];
                if ($item['variation_id']) {
                    $stock = Stock::where('variation_id', $item['variation_id'])->first();
                } else {
                    $stock = Stock::where('product_id', $item['product_id'])
                        ->whereNull('variation_id')
                        ->first();
                }

                if ($stock && $stock->quantity >= $quantity) {
                    $cart[$key]['quantity'] = $quantity;
                } else {
                    return back()->with('error', 'Quantidade em estoque insuficiente para ' . $item['nome']);
                }
            }
        }
        Session::put('montinks_cart', $cart);
        return back()->with('success', 'Carrinho atualizado!');
    }

    public function searchCEP(Request $request)
    {
        $cep = preg_replace('/[^0-9]/', '', $request->cep);
        $url = "https://viacep.com.br/ws/{$cep}/json/";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response);

        if (isset($data->erro)) {
            return response()->json(['error' => 'CEP não encontrado'], 404);
        }

        return response()->json([
            'address' => "{$data->logradouro}, {$data->bairro}, {$data->localidade} - {$data->uf}"
        ]);
    }

    public function deliverySimulator(Request $request)
    {
        $subtotal = $request->subtotal;
        $delivery = 20.00;

        if ($subtotal >= 52.00 && $subtotal <= 166.59) {
            $delivery = 15.00;
        } elseif ($subtotal > 200.00) {
            $delivery = 0.00;
        }

        return response()->json(['delivery' => number_format($delivery, 2, ',', '.')]);
    }

    public function checkout(Request $request)
    {
        $cart = Session::get('montinks_cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Carrinho vazio!');
        }

        // Calcular subtotal
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Calcular frete
        $delivery = 20.00;
        if ($subtotal >= 52.00 && $subtotal <= 166.59) {
            $delivery = 15.00;
        } elseif ($subtotal > 200.00) {
            $delivery = 0.00;
        }

        $total = $subtotal + $delivery;

        $order = Order::create([
            'session_id' => Session::getId(),
            'subtotal' => $subtotal,
            'delivery' => $delivery,
            'total' => $total,
            'cep' => $request->cep,
            'address' => $request->address,
            'status' => 'pending',
        ]);

        // Atualizar estoque
        foreach ($cart as $item) {
            if ($item['variation_id']) {
                $stock = Stock::where('variation_id', $item['variation_id'])->first();
            } else {
                $stock = Stock::where('product_id', $item['product_id'])
                    ->whereNull('variation_id')
                    ->first();
            }

            if ($stock) {
                $stock->decrement('quantity', $item['quantity']);
            }
        }

        Session::forget('montinks_cart');
        return redirect()->route('products.index')->with('success', 'Pedido realizado com sucesso!');
    }
}
