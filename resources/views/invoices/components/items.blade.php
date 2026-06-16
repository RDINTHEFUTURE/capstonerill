@props(['oldItems' => null, 'chartOfAccounts' => collect()])

@php
    $items = $oldItems ?? [];
@endphp

<div class="card invoice-items-card mt-3">
    <div class="card-header">
        <h5 class="card-title mb-0">Produk</h5>
    </div>
    <div class="card-body">
        <div id="items-container" class="invoice-items-container">
            @if (count($items) === 0)
                <div class="item-row invoice-item-row mb-3">
                    <div class="row g-2">
                        <div class="col-md-3 col-12">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" name="items[0][nama_produk]" class="item-nama form-control" value="" maxlength="255" required>
                        </div>
                        <div class="col-md-3 col-12">
                            <label class="form-label">Akun</label>
                            <select name="items[0][chart_of_account_no_new]" class="item-account form-select">
                                <option value="">- Pilih Akun -</option>
                                @foreach ($chartOfAccounts as $account)
                                    <option value="{{ $account->account_no_new }}">{{ $account->account_no_new }} - {{ $account->account_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1 col-6">
                            <label class="form-label">Qty</label>
                            <input type="number" min="1" step="1" name="items[0][qty]" class="item-qty form-control" value="1" required>
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label">Harga</label>
                            <input type="number" min="0" step="0.01" name="items[0][harga]" class="item-harga form-control" value="0" required>
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label">Diskon</label>
                            <input type="number" min="0" step="0.01" name="items[0][diskon]" class="item-diskon form-control" value="0">
                        </div>
                        <div class="col-md-1 col-6">
                            <label class="form-label">Subtotal</label>
                            <input type="number" name="items[0][subtotal]" class="item-subtotal form-control" value="0" readonly>
                        </div>
                    </div>
                </div>
            @else
                @foreach ($items as $idx => $it)
                    <div class="item-row invoice-item-row mb-3">
                        <div class="row g-2">
                            <div class="col-md-3 col-12">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" name="items[{{ $idx }}][nama_produk]" class="item-nama form-control" value="{{ $it['nama_produk'] ?? '' }}" maxlength="255" required>
                            </div>
                            <div class="col-md-3 col-12">
                                <label class="form-label">Akun</label>
                                <select name="items[{{ $idx }}][chart_of_account_no_new]" class="item-account form-select">
                                    <option value="">- Pilih Akun -</option>
                                    @foreach ($chartOfAccounts as $account)
                                        <option value="{{ $account->account_no_new }}" @selected(($it['chart_of_account_no_new'] ?? '') === $account->account_no_new)>
                                            {{ $account->account_no_new }} - {{ $account->account_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 col-6">
                                <label class="form-label">Qty</label>
                                <input type="number" min="1" step="1" name="items[{{ $idx }}][qty]" class="item-qty form-control" value="{{ $it['qty'] ?? 1 }}" required>
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label">Harga</label>
                                <input type="number" min="0" step="0.01" name="items[{{ $idx }}][harga]" class="item-harga form-control" value="{{ $it['harga'] ?? 0 }}" required>
                            </div>
                            <div class="col-md-2 col-6">
                                <label class="form-label">Diskon</label>
                                <input type="number" min="0" step="0.01" name="items[{{ $idx }}][diskon]" class="item-diskon form-control" value="{{ $it['diskon'] ?? 0 }}">
                            </div>
                            <div class="col-md-1 col-6">
                                <label class="form-label">Subtotal</label>
                                <input type="number" name="items[{{ $idx }}][subtotal]" class="item-subtotal form-control" value="{{ $it['subtotal'] ?? 0 }}" readonly>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <button type="button" class="btn btn-primary" id="add-item">
            + Tambah Produk
        </button>
    </div>
</div>
