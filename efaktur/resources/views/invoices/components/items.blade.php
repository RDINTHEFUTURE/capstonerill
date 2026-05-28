@props(['oldItems' => null])

@php
    $items = $oldItems ?? [];
@endphp

    <div class="card invoice-items-card">
    <h3 class="section-title">Produk</h3>

    <div id="items-container" class="invoice-items-container">
        @if (count($items) === 0)
            @php($i = 0)
            <div class="item-row invoice-item-row">
                <div>
                    <label>Nama Produk</label>
                    <input type="text" name="items[0][nama_produk]" class="item-nama" value="" maxlength="255" required>
                </div>
                <div>
                    <label>Qty</label>
                    <input type="number" min="1" step="1" name="items[0][qty]" class="item-qty" value="1" required>
                </div>
                <div>
                    <label>Harga</label>
                    <input type="number" min="0" step="0.01" name="items[0][harga]" class="item-harga" value="0" required>
                </div>
                <div>
                    <label>Diskon</label>
                    <input type="number" min="0" step="0.01" name="items[0][diskon]" class="item-diskon" value="0">
                </div>
                <div>
                    <label>Subtotal</label>
                    <input type="number" name="items[0][subtotal]" class="item-subtotal" value="0" readonly>
                </div>
                <div class="item-debug"></div>
            </div>
        @else
            @foreach ($items as $idx => $it)
                <div class="item-row invoice-item-row">
                    <div>
                        <label>Nama Produk</label>
                        <input type="text" name="items[{{ $idx }}][nama_produk]" class="item-nama" value="{{ $it['nama_produk'] ?? '' }}" maxlength="255" required>
                    </div>
                    <div>
                        <label>Qty</label>
                        <input type="number" min="1" step="1" name="items[{{ $idx }}][qty]" class="item-qty" value="{{ $it['qty'] ?? 1 }}" required>
                    </div>
                    <div>
                        <label>Harga</label>
                        <input type="number" min="0" step="0.01" name="items[{{ $idx }}][harga]" class="item-harga" value="{{ $it['harga'] ?? 0 }}" required>
                    </div>
                    <div>
                        <label>Diskon</label>
                        <input type="number" min="0" step="0.01" name="items[{{ $idx }}][diskon]" class="item-diskon" value="{{ $it['diskon'] ?? 0 }}">
                    </div>
                    <div>
                        <label>Subtotal</label>
                        <input type="number" name="items[{{ $idx }}][subtotal]" class="item-subtotal" value="{{ $it['subtotal'] ?? 0 }}" readonly>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <button type="button" class="btn invoice-btn-add" id="add-item">
        + Tambah Produk
    </button>
</div>

