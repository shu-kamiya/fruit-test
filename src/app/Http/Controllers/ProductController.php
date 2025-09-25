<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Season;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'price_asc');
        $perPage = 6;

        $query = Product::query();

        $keyword = $request->input('keyword');
        if (!empty($keyword)) {
            $query->where('name', 'LIKE', "%{$keyword}%");
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
            'keyword'   => $keyword,
            'sort'     => $sort,
        ]);
    }

    public function show($productId)
    {
        $product = Product::findOrFail($productId);
        $seasons = Season::all();
        return view('products.detail', compact('product', 'seasons'));
    }

    public function create()
    {
        $seasons = Season::all();
        return view('products.register', compact('seasons'));
    }

    public function edit($productId)
    {
        $product = Product::findOrFail($productId);
        $seasons = Season::all();
        return view('products.edit', compact('product', 'seasons'));
    }

    public function store(ProductRequest $request)
    {
        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'name'        => $request->name,
            'price'       => $request->price,
            'description' => $request->description,
            'image'       => $path,
        ]);

        if ($request->filled('seasons')) {
            $product->seasons()->attach($request->seasons);
        }

        return redirect('/products')->with('success', '商品を登録しました');
    }

    public function update(ProductRequest $request, $productId)
    {
        $product = Product::findOrFail($productId);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
        } else {
            $path = $product->image;
        }

        $product->update([
            'name'        => $request->input('name'),
            'price'       => $request->input('price'),
            'description' => $request->input('description'),
            'image'       => $path,
        ]);

        $product->seasons()->sync($request->seasons ?? []);

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
