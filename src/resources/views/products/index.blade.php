@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="add-btn-wrapper">
    <a href="{{ url('/products/register') }}" class="add-btn">+ 商品を追加</a>
    </div>
    @if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
    @endif
    <div class="sidebar">
        <form action="{{ url('/products/search') }}" method="get">
            <input type="text" name="search" placeholder="商品名で検索" value="{{ request('search') }}">
            <button type="submit">検索</button>
        </form>

        <form action="{{ url('/products') }}" method="get">
            <select name="sort" onchange="this.form.submit()">
                <option value="">価格順で表示</option>
                <option value="asc">安い順</option>
                <option value="desc">高い順</option>
            </select>
        </form>
    </div>

    <div class="main-content">
        <div class="product-list">
            @if ($products->count() > 0)
            @foreach ($products as $product)
            <div class="product-card">
                <a href="/products/{{ $product->id }}">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                    <h3>{{ $product->name }}</h3>
                    <p>¥{{ number_format($product->price) }}</p>
                </a>
            </div>
            @endforeach
            @else
                <p>該当する商品は見つかりませんでした。</p>
            @endif
        </div>
        <div class="pagination">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection