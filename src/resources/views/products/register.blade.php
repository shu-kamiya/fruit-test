@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="register-form">
    <h2 class="register-form__heading content__heading">商品登録</h2>
    <div class="register-form__inner">
        <form action="/products/register" method="post" enctype="multipart/form-data">
            @csrf
            <div class="register-form__group">
                <label class="register-form__label" for="name">
                    商品名<span class="register-form__required">必須</span>
                </label>
                <input class="register-form__input" type="text" name="name" id="name" value="{{ old('name') }}" placeholder="商品名を入力">
                <p class="register-form__error-message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="register-form__group">
                <label class="register-form__label">
                    値段<span class="register-form__required">必須</span>
                </label>
                <input class="register-form__input" type="number" name="price" id="price" value="{{ old('price') }}" placeholder="値段を入力">
                <p class="register-form__error-message">
                    @error('price')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="register-form__group">
                <label class="register-form__label">
                    商品画像<span class="register-form__required">必須</span>
                </label>
                <input class="register-form__input" type="file" name="image" id="image" accept="image/*">
                <br>
                <img src="" id="preview" alt="プレビュー画像" style="max-width: 200px; display: none;">
                <p class="register-form__error-message">
                    @error('image')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="register-form__group">
                <label class="register-form__label">
                    季節<span class="register-form__required">必須</span>
                    <span class="register-form__optional">複数選択可</span>
                </label>
                <div class="season-options">
                    @foreach($seasons as $season)
                    <label class="season-label">
                        <input type="checkbox" name="seasons[]" value="{{ $season->id }}" class="season-checkbox">
                        <span>{{ $season->name }}</span>
                    </label>
                    @endforeach
                </div>
                <p class="register-form__error-message">
                    @error('season')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="register-form__group">
                <label class="register-form__label">
                    商品説明<span class="register-form__required">必須</span>
                </label>
                <textarea class="register-form__textarea" name="description" id="" cols="30" rows="10" placeholder="商品の説明を入力">{{ old('description') }}</textarea>
                <p class="register-form__error-message">
                    @error('description')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="register-form__btn-inner">
                <input class="register-form__send-btn btn" type="submit" value="登録" name="send">
                <a class="register-form__back-btn btn" href="/products">戻る</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('script')
<script>
document.getElementById('image').addEventListener('change', function (e) {
    const reader = new FileReader();
    reader.onload = function (e) {
        const preview = document.getElementById('preview');
        preview.src = e.target.result;
        preview.style.display = 'block';
    };
    if (this.files[0]) {
        reader.readAsDataURL(this.files[0]);
    }
});
</script>
@endpush