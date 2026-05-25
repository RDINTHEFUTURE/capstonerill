@props(['invoice' => null])

<div>
    <label>Nomor</label>
    <input name="nomor" value="{{ old('nomor', $invoice?->nomor) }}" required maxlength="255">
    @error('nomor') <div class="error">{{ $message }}</div> @enderror

    <label>Tanggal</label>
    <input type="date" name="tanggal" value="{{ old('tanggal', $invoice?->tanggal?->format('Y-m-d')) }}" required>
    @error('tanggal') <div class="error">{{ $message }}</div> @enderror

    <label>NPWP</label>
    <input name="npwp" value="{{ old('npwp', $invoice?->npwp) }}" maxlength="32">
    @error('npwp') <div class="error">{{ $message }}</div> @enderror

    <label>Nama</label>
    <input name="nama" value="{{ old('nama', $invoice?->nama) }}" maxlength="255">
    @error('nama') <div class="error">{{ $message }}</div> @enderror

    <label>Alamat</label>
    <textarea name="alamat" rows="3" maxlength="255">{{ old('alamat', $invoice?->alamat) }}</textarea>
    @error('alamat') <div class="error">{{ $message }}</div> @enderror

    <label>Total</label>
    <input name="total" type="number" step="0.01" value="{{ old('total', $invoice?->total) }}" required>
    @error('total') <div class="error">{{ $message }}</div> @enderror

    <label>Mata Uang (currency)</label>
    <input name="currency" value="{{ old('currency', $invoice?->currency ?? 'IDR') }}" maxlength="3">
    @error('currency') <div class="error">{{ $message }}</div> @enderror
</div>

