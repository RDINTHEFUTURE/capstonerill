@props(['oldItems' => null])

@php
    $items = $oldItems ?? [];
@endphp

<div class="card" style="border:1px solid #e5e7eb;border-radius:12px;padding:16px;margin-top:16px;">
    <h3 style="margin:0 0 12px;">Produk</h3>

    <div id="items-container">
        @if (count($items) === 0)
            @php($i = 0)
            <div class="item-row" style="display:grid;grid-template-columns: 2fr 1fr 1.5fr 1.5fr 0.8fr;gap:10px;align-items:end;margin-bottom:10px;">
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
                <div style="grid-column: 1 / -1; display:none;" class="item-debug"></div>
            </div>
        @else
            @foreach ($items as $idx => $it)
                <div class="item-row" style="display:grid;grid-template-columns: 2fr 1fr 1.5fr 1.5fr 0.8fr;gap:10px;align-items:end;margin-bottom:10px;">
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

    <button type="button" class="btn" id="add-item" style="margin-top:8px;background:#2563eb;color:#fff;border:none;border-radius:8px;padding:10px 14px;cursor:pointer;">
        + Tambah Produk
    </button>
</div>

