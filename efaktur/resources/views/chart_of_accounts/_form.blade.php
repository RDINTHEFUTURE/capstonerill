<div>
    <label>Kode Akun</label>
    <input name="account_no_new" value="{{ old('account_no_new', $chartOfAccount?->account_no_new) }}" maxlength="6" @if($chartOfAccount) readonly @endif required>
    @error('account_no_new') <div class="error">{{ $message }}</div> @enderror

    <label>Kode Lama 1</label>
    <input name="account_no_old_1" value="{{ old('account_no_old_1', $chartOfAccount?->account_no_old_1) }}" maxlength="4">
    @error('account_no_old_1') <div class="error">{{ $message }}</div> @enderror

    <label>Kode Lama 2</label>
    <input name="account_no_old_2" type="number" step="1" value="{{ old('account_no_old_2', $chartOfAccount?->account_no_old_2) }}">
    @error('account_no_old_2') <div class="error">{{ $message }}</div> @enderror

    <label>Nama Akun</label>
    <input name="account_name" value="{{ old('account_name', $chartOfAccount?->account_name) }}" maxlength="37" required>
    @error('account_name') <div class="error">{{ $message }}</div> @enderror

    <label>Header?</label>
    <select name="is_header">
        <option value="" @selected(old('is_header', $chartOfAccount?->is_header) === '' || old('is_header', $chartOfAccount?->is_header) === null)>Tidak</option>
        <option value="H" @selected(old('is_header', $chartOfAccount?->is_header) === 'H')>Ya</option>
    </select>
    @error('is_header') <div class="error">{{ $message }}</div> @enderror

    <label>Tipe Akun</label>
    <input name="account_type" value="{{ old('account_type', $chartOfAccount?->account_type) }}" maxlength="19">
    @error('account_type') <div class="error">{{ $message }}</div> @enderror
</div>
