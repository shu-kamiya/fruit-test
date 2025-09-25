@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="product-form">
    <h2 class="product-form__heading">商品編集</h2>

    <form action="{{ url('/products/' .  $product->id . '/update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="product-form__main">
            <div class="product-form__image-section">
                <label class="product-form__label">商品画像</label>
                <div class="product-form__image-preview">
                    @if($product->image)
                    <img id="preview" src="{{ asset('storage/' . $product->image) }}" alt="商品画像">
                    @endif
                </div>
                <div class="product-form__file-input-wrapper">
                    <label for="imageInput" class="product-form__file-label">ファイルを選択</label>
                    <input type="file" name="image" id="imageInput" class="product-form__file-input">
                    <span id="fileName" class="product-form__file-name">@if($product->image) {{ $product->image }} @else 選択されていません @endif</span>
                </div>
                @error('image')
                <p class="product-form__error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="product-form__details-section">
                <div class="product-form__group">
                    <label class="product-form__label">商品名</label>
                    <input class="product-form__input" type="text" name="name" value="{{ old('name', $product->name) }}">
                    @error('name')
                    <p class="product-form__error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="product-form__group">
                    <label class="product-form__label">値段</label>
                    <input class="product-form__input" type="number" name="price" value="{{ old('price', $product->price) }}">
                    @error('price')
                    <p class="product-form__error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="product-form__group">
                    <label class="product-form__label">季節</label>
                    <div class="product-form__seasons">
                        @foreach($seasons as $season)
                        <label>
                            <input type="checkbox" name="seasons[]" value="{{ $season->id }}" {{ $product->seasons->contains($season->id) ? 'checked' : '' }}>
                            {{ $season->name }}
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="product-form__group">
            <label class="product-form__label">商品説明</label>
            <textarea class="product-form__textarea" name="description">{{ old('description', $product->description) }}</textarea>
            @error('description')
            <p class="product-form__error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="product-form__btn-inner">
            <a href="/products" class="product-form__back-btn btn">戻る</a>
            <button type="submit" class="product-form__update-btn btn">変更を保存</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('imageInput').addEventListener('change', function(e) {
        const [file] = e.target.files;
        if (file) {
            const preview = document.getElementById('preview');
            preview.src = URL.createObjectURL(file);
            document.getElementById('fileName').textContent = file.name;
        }
    });
</script>
@endsection