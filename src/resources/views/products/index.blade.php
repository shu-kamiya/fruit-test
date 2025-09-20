@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="container">
    @if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
    @endif
    <div class="sidebar">
        <form action="{{ url('/products/search') }}" method="get">
            <input type="text" name="keyword" placeholder="商品名で検索">
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

    <div class="product-list">
        @foreach ($products as $product)
        <div class="product-card">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
            <h3>{{ $product->name }}</h3>
            <p>¥{{ number_format($product->price) }}</p>
        </div>
        @endforeach
    </div>

    <div class="pagination">
        {{ $products->links() }}
    </div>
</div>
@endsection