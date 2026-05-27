<div>
    <label>Nomor</label>
    <input name="nomor" value="{{ old('nomor', $invoice->nomor ?? '') }}" required maxlength="255">
    @error('nomor') <div class="error">{{ $message }}</div> @enderror

    <label>Tanggal</label>
    <input type="date" name="tanggal" value="{{ old('tanggal', isset($invoice) ? $invoice->tanggal->format('Y-m-d') : '') }}" required>
    @error('tanggal') <div class="error">{{ $message }}</div> @enderror

    <h3 style="margin:16px 0 8px;">Penjual</h3>

    <label>NPWP Penjual</label>
    <input name="npwp_penjual" value="{{ old('npwp_penjual', $invoice->npwp_penjual ?? '') }}" maxlength="32">
    @error('npwp_penjual') <div class="error">{{ $message }}</div> @enderror

    <label>Nama Penjual</label>
    <input name="nama_penjual" value="{{ old('nama_penjual', $invoice->nama_penjual ?? '') }}" maxlength="255">
    @error('nama_penjual') <div class="error">{{ $message }}</div> @enderror

    <label>Alamat Penjual</label>
    <textarea name="alamat_penjual" rows="3" maxlength="255">{{ old('alamat_penjual', $invoice->alamat_penjual ?? '') }}</textarea>
    @error('alamat_penjual') <div class="error">{{ $message }}</div> @enderror

    <h3 style="margin:16px 0 8px;">Pembeli</h3>

    <label>NPWP Pembeli</label>
    <input name="npwp_pembeli" value="{{ old('npwp_pembeli', $invoice->npwp_pembeli ?? '') }}" maxlength="32">
    @error('npwp_pembeli') <div class="error">{{ $message }}</div> @enderror

    <label>Nama Pembeli</label>
    <input name="nama_pembeli" value="{{ old('nama_pembeli', $invoice->nama_pembeli ?? '') }}" maxlength="255">
    @error('nama_pembeli') <div class="error">{{ $message }}</div> @enderror

    <label>Alamat Pembeli</label>
    <textarea name="alamat_pembeli" rows="3" maxlength="255">{{ old('alamat_pembeli', $invoice->alamat_pembeli ?? '') }}</textarea>
    @error('alamat_pembeli') <div class="error">{{ $message }}</div> @enderror


    <label>Total</label>
    <input name="total" type="number" step="0.01" value="{{ old('total', $invoice->total ?? '') }}" required>
    @error('total') <div class="error">{{ $message }}</div> @enderror

    <label>Mata Uang (currency)</label>
    <input name="currency" value="{{ old('currency', $invoice->currency ?? 'IDR') }}" maxlength="3">
    @error('currency') <div class="error">{{ $message }}</div> @enderror
</div>


