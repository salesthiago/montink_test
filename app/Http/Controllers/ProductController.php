<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use App\Models\Variation;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['variations', 'stocks'])->get();

        return view('products.index', [
            'products' => $products,
            'cartData' => json_encode(session('montinks_cart', []))
        ]);
    }

    public function create()
    {
        $product = new Product();
        return view('products.create', ['product' => $product]);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $this->validateProduct($request);

        $price = str_replace('.', '', $data['price']);
        $price = str_replace(',', '.', $price);

        $product = Product::create([
            'name' => $data['name'],
            'price' => $price
        ]);


        if (empty($data['variations'])) {
            Stock::create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        } else {
            foreach ($data['variations'] as $item) {
                $amount = str_replace('.', '', $item['price']);
                $amount = str_replace(',', '.', $amount);
                $variation = Variation::create([
                    'product_id' => $product->id,
                    'name' => $item['name'],
                    'price' => $amount
                ]);

                Stock::create([
                    'product_id' => $product->id,
                    'variation_id' => $variation->id,
                    'quantity' => $item['quantity']
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Produto Criado com sucesso');

    }

     public function edit(Product $product)
    {
        $product->load(['variations', 'stocks']);
        return view('products.edit', compact('product'));
    }

    public function update($id, Request $request)
    {
        $product = Product::with(['variations.stocks', 'stocks'])->findOrFail($id);
        $this->validateProduct($request);
        $data = $request->all();
        $price = str_replace('.', '', $data['price']);
        $price = str_replace(',', '.', $price);


        $product->update([
            'name' => $data['name'],
            'price' => $price
        ]);


        if (empty($data['variations'])) {
            if ($product->stocks) {
                $product->stocks()->update([
                    'quantity' => $data['quantity']
                ]);
            } else {
                Stock::create([
                    'product_id' => $product->id,
                    'quantity' => $data['quantity'],
                ]);
            }
        } else {
            $variationsIds = [];
            foreach ($data['variations'] as $item) {
                $amount = str_replace('.', '', $item['price']);
                $amount = str_replace(',', '.', $amount);

                if (empty($item['id'])) {
                    $variation = Variation::create([
                        'product_id' => $product->id,
                        'name' => $item['name'],
                        'price' => $item['price']
                    ]);
                    $variationsIds[] = $variation->id;
                } else {
                    $variation = Variation::find($item['id']);
                    $variation->update([
                        'name' => $item['name'],
                        'price' => $amount
                    ]);
                }
                $stock = Stock::where('variation_id', $variation->id)->first();
                if ($stock) {
                    $stock->update([
                        'quantity' => (int)$item['quantity']
                    ]);
                } else {
                    Stock::create([
                        'product_id' => $product->id,
                        'variation_id' => $variation->id,
                        'quantity' => (int)$item['quantity']
                    ]);

                }
            }
            if (sizeof($variationsIds) > 0) {
                $product->variations()->whereNotIn('id', $variationsIds)->delete();
            }
        }

        return redirect()->route('products.index')->with('success', 'Produto atualizado com sucesso');

    }
    private function validateProduct(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required',
            'quantity' => 'required|regex:/^\d+(,\d{1,2})?$/',
            'variations' => 'sometimes|array',
            'variations.*.name' => 'required_with:variations|string|max:255',
            'variations.*.price' => 'required_with:variations|regex:/^\d+(,\d{1,2})?$/',
            'variations.*.quantity' => 'required_with:variations|string|max:255'
        ]);
    }
}
