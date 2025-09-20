<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $sort = $request->input('sort', 'price_asc');
        $perPage = 6;

        $query = Product::query();

        if (!empty($search)) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->orderBy('id', 'asc');
        }

        $products = $query->paginate($perPage)->withQueryString();

        return view('products.index', [
            'products' => $products,
            'search'   => $search,
            'sort'     => $sort,
        ]);
    }

    public function show($productId)
    {
        $product = Product::findOrFail($productId);
        return view('products.show', compact('product'));
    }

    public function create()
    {
        return view('products.register');
    }

    public function edit($productId)
    {
        $product = Product::findOrFail($productId);
        return view('products.edit', compact('product'));
    }

    public function store(ProductRequest $request)
    {
        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name'        => $request->name,
            'price'       => $request->price,
            'season'      => $request->season,
            'description' => $request->description,
            'image'       => $path,
        ]);

        return redirect('/products')->with('success', '商品を登録しました');
    }

    public function update(ProductRequest $request, $productId)
    {
        $product = Product::findOrFail($productId);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $path = $request->file('image')->store('products', 'public');

        $product->update([
            'name'        => $request->input('name'),
            'price'       => $request->input('price'),
            'season'      => $request->input('season'),
            'description' => $request->input('description'),
            'image'       => $path,
        ]);

        return redirect('/products')->with('success', '商品を更新しました');
    }

    public function search(Request $request)
    {
        $search = $request->input('search', '');
        $sort = $request->input('sort', 'price_asc');
        $perPage = 6;

        $query = Product::query();

        if (!empty($search)) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->orderBy('id', 'asc');
        }

        $products = $query->paginate($perPage)->withQueryString();

        return view('products.index', compact('products', 'search', 'sort'));
    }

    public function destroy($productId)
    {
        $product = Product::findOrFail($productId);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect('/products')->with('success', '商品を削除しました');
    }
}
