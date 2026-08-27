<h3>{{ $product->name }}</h3>
<p class="text-danger fs-4 fw-bold">{{ number_format($product->price, 0, ',', '.') }} ₫</p>

<!-- Chọn Size -->
<div class="mb-3">
    <label class="fw-bold d-block mb-2">Kích cỡ:</label>
    @foreach($product->variants->pluck('size')->unique('id') as $size)
        <button class="btn btn-outline-dark btn-sm me-2">{{ $size->name }}</button>
    @endforeach
</div>

<!-- Chọn Màu -->
<div class="mb-3">
    <label class="fw-bold d-block mb-2">Màu sắc:</label>
    @foreach($product->variants->pluck('color')->unique('id') as $color)
        <span class="badge bg-secondary p-2 me-2">{{ $color->name }}</span>
    @endforeach
</div>