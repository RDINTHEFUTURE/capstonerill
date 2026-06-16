<div class="row">
    <div class="col-md-6 col-12">
        <div class="form-group mb-3">
            <label class="form-label">Kode Akun</label>
            <input name="account_no_new" class="form-control" value="{{ old('account_no_new', $chartOfAccount?->account_no_new) }}" maxlength="6" @if($chartOfAccount) readonly @endif required>
            @error('account_no_new') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="form-group mb-3">
            <label class="form-label">Kode Lama 1</label>
            <input name="account_no_old_1" class="form-control" value="{{ old('account_no_old_1', $chartOfAccount?->account_no_old_1) }}" maxlength="4">
            @error('account_no_old_1') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="form-group mb-3">
            <label class="form-label">Kode Lama 2</label>
            <input name="account_no_old_2" type="number" step="1" class="form-control" value="{{ old('account_no_old_2', $chartOfAccount?->account_no_old_2) }}">
            @error('account_no_old_2') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="form-group mb-3">
            <label class="form-label">Nama Akun</label>
            <input name="account_name" class="form-control" value="{{ old('account_name', $chartOfAccount?->account_name) }}" maxlength="37" required>
            @error('account_name') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="form-group mb-3">
            <label class="form-label">Header?</label>
            <select name="is_header" class="form-select">
                <option value="" @selected(old('is_header', $chartOfAccount?->is_header) === '' || old('is_header', $chartOfAccount?->is_header) === null)>Tidak</option>
                <option value="H" @selected(old('is_header', $chartOfAccount?->is_header) === 'H')>Ya</option>
            </select>
            @error('is_header') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="form-group mb-3">
            <label class="form-label">Tipe Akun</label>
            <input name="account_type" class="form-control" value="{{ old('account_type', $chartOfAccount?->account_type) }}" maxlength="19">
            @error('account_type') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
