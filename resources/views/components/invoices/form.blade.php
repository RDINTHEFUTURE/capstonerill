@props(['invoice' => null, 'chartOfAccounts' => collect()])

<div>
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="form-group mb-3">
                <label class="form-label">Nomor Seri Faktur Pajak</label>
                <input name="nomor" type="text" class="form-control" value="{{ old('nomor', $invoice?->nomor) }}" required maxlength="255">
                @error('nomor') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="form-group mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $invoice?->tanggal?->format('Y-m-d')) }}" required>
                @error('tanggal') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    <h4 class="mt-3 mb-3">Penjual</h4>
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="form-group mb-3">
                <label class="form-label">NPWP Penjual</label>
                <input name="npwp_penjual" class="form-control" value="{{ old('npwp_penjual', $invoice?->npwp_penjual) }}" maxlength="32">
                @error('npwp_penjual') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="form-group mb-3">
                <label class="form-label">Nama Penjual</label>
                <input name="nama_penjual" class="form-control" value="{{ old('nama_penjual', $invoice?->nama_penjual) }}" maxlength="255">
                @error('nama_penjual') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group mb-3">
                <label class="form-label">Alamat Penjual</label>
                <textarea name="alamat_penjual" class="form-control" rows="3" maxlength="255">{{ old('alamat_penjual', $invoice?->alamat_penjual) }}</textarea>
                @error('alamat_penjual') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    <h4 class="mt-3 mb-3">Pembeli</h4>
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="form-group mb-3">
                <label class="form-label">NPWP Pembeli</label>
                <input name="npwp_pembeli" class="form-control" value="{{ old('npwp_pembeli', $invoice?->npwp_pembeli) }}" maxlength="32">
                @error('npwp_pembeli') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="form-group mb-3">
                <label class="form-label">Nama Pembeli</label>
                <input name="nama_pembeli" class="form-control" value="{{ old('nama_pembeli', $invoice?->nama_pembeli) }}" maxlength="255">
                @error('nama_pembeli') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group mb-3">
                <label class="form-label">Alamat Pembeli</label>
                <textarea name="alamat_pembeli" class="form-control" rows="3" maxlength="255">{{ old('alamat_pembeli', $invoice?->alamat_pembeli) }}</textarea>
                @error('alamat_pembeli') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    @include('invoices.components.items', ['oldItems' => old('items', $invoice?->items?->toArray() ?? []), 'chartOfAccounts' => $chartOfAccounts])

    <div class="row mt-3">
        <div class="col-md-6 col-12">
            <div class="form-group mb-3">
                <label class="form-label">Total</label>
                <input name="total" id="invoice-total" type="number" step="0.01" class="form-control" value="{{ old('total', $invoice?->total ?? 0) }}" required readonly>
                @error('total') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="form-group mb-3">
                <label class="form-label">Mata Uang</label>
                <input name="currency" class="form-control" value="{{ old('currency', $invoice?->currency ?? 'IDR') }}" maxlength="3">
                @error('currency') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="form-group mb-3">
                <label class="form-label">Pihak Penandatangan</label>
                <input name="role_penandatangan" class="form-control" value="{{ old('role_penandatangan', $invoice?->role_penandatangan ?? '') }}" maxlength="255">
                @error('role_penandatangan') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group mb-3">
                <label class="form-label">Catatan / Notes</label>
                <textarea name="notes" class="form-control" rows="3" maxlength="1000">{{ old('notes', $invoice?->notes) }}</textarea>
                @error('notes') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="form-group mb-3">
                <label class="form-label">Upload QR Bukti Tanda Tangan Digital (DJP)</label>
                <input type="file" name="qr_image" class="form-control" accept="image/png,image/jpeg" {{ (old('signature_type', $invoice?->signature_type ?? 'qr') === 'hand') ? 'disabled' : '' }}>
                <div class="form-text">Jika tidak diupload saat edit, QR sebelumnya akan tetap digunakan.</div>
                @error('qr_image') <div class="text-danger small">{{ $message }}</div> @enderror

                @if(isset($invoice) && $invoice->qr_image)
                    <div class="mt-2" id="qr-preview-wrapper">
                        <label class="form-label">Preview QR saat ini</label>
                        <div style="width:120px; height:120px; border:1px solid var(--bs-border-color, #ddd); display:flex; align-items:center; justify-content:center;">
                            <img src="{{ $invoice->qr_image }}" alt="QR" style="max-width:100%; max-height:100%;" />
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="hand-signature-wrapper" style="display: none;">
        <div class="form-group mb-3">
            <label class="form-label">Signature Digital</label>
            <div class="signature-card">
                <canvas id="signature-canvas" width="280" height="140"></canvas>
                <div class="signature-pad-actions">
                    <button type="button" class="btn btn-secondary" id="clear-signature">Clear Signature</button>
                </div>
                <div class="signature-pad-info">Gunakan mouse atau sentuhan untuk menggambar tanda tangan. Kosongkan jika tidak ingin merubah tanda tangan saat edit.</div>
            </div>
            <div id="signature-preview-wrapper" class="invoice-signature-preview-wrapper">
                <label class="form-label">Preview Tanda Tangan</label>
                <img id="signature-preview" src="" alt="Preview Tanda Tangan" class="invoice-signature-preview-image" />
            </div>
        </div>
        <div class="form-group mb-3">
            <label class="form-label">Nama Penandatangan</label>
            <input type="text" name="signature_name" id="signature_name" class="form-control" value="{{ old('signature_name', $invoice?->signature_name) }}" maxlength="255">
            @error('signature_name') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.0/dist/signature_pad.umd.min.js"></script>
    <script>
        (function(){
            const container = document.getElementById('items-container');

            if(!container) return;

            function parseNumber(v){
                const n = parseFloat(String(v ?? '').replace(/,/g,''));
                return Number.isFinite(n) ? n : 0;
            }

            function updateRow(row){
                const qtyEl = row.querySelector('.item-qty');
                const hargaEl = row.querySelector('.item-harga');
                const diskonEl = row.querySelector('.item-diskon');
                const subtotalEl = row.querySelector('.item-subtotal');

                const qty = parseNumber(qtyEl?.value);
                const harga = parseNumber(hargaEl?.value);
                const diskon = parseNumber(diskonEl?.value);

                let subtotal = (harga * qty) - diskon;
                if(subtotal < 0) subtotal = 0;

                if(subtotalEl) subtotalEl.value = subtotal.toFixed(2);
            }

            function updateTotal(){
                let total = 0;
                container.querySelectorAll('.item-row').forEach(row => {
                    const subtotal = parseNumber(row.querySelector('.item-subtotal')?.value);
                    total += subtotal;
                });
                const totalEl = document.getElementById('invoice-total');
                if(totalEl) totalEl.value = total.toFixed(2);
            }

            function bindRowEvents(row){
                ['input','change'].forEach(evt => {
                    row.querySelectorAll('input').forEach(inp => {
                        inp.addEventListener(evt, () => {
                            updateRow(row);
                            updateTotal();
                        });
                    });
                });
            }

            // bind
            container.querySelectorAll('.item-row').forEach(row => {
                bindRowEvents(row);
                updateRow(row);
            });
            updateTotal();

            // add item button: clone baris pertama

            const addBtn = document.getElementById('add-item');
            if(addBtn){
                addBtn.addEventListener('click', () => {
                    const rows = container.querySelectorAll('.item-row');
                    if(!rows.length) return;
                    const template = rows[0];
                    const newIndex = rows.length;
                    const clone = template.cloneNode(true);

                    // reset values
                    clone.querySelectorAll('input').forEach(inp => {
                        if(inp.classList.contains('item-qty')) inp.value = 1;
                        else if(inp.classList.contains('item-harga')) inp.value = 0;
                        else if(inp.classList.contains('item-diskon')) inp.value = 0;
                        else if(inp.classList.contains('item-subtotal')) inp.value = 0;

                        const name = inp.getAttribute('name');
                        if(name){
                            inp.setAttribute('name', name.replace(/items\[\d+\]/, `items[${newIndex}]`));
                        }
                    });

                    clone.querySelectorAll('select').forEach(sel => {
                        sel.selectedIndex = 0;
                        const name = sel.getAttribute('name');
                        if(name){
                            sel.setAttribute('name', name.replace(/items\[\d+\]/, `items[${newIndex}]`));
                        }
                    });

                    container.appendChild(clone);
                    bindRowEvents(clone);
                    updateRow(clone);
                    updateTotal();
                });
            }

        })();

        (function(){
            const canvas = document.getElementById('signature-canvas');
            const hiddenInput = document.getElementById('signature_data');
            const clearButton = document.getElementById('clear-signature');
            const previewWrapper = document.getElementById('signature-preview-wrapper');
            const previewImage = document.getElementById('signature-preview');
            const form = canvas?.closest('form');
            const existingSignature = {!! json_encode(old('signature_data', $invoice?->signature_data)) !!} || '';

            if(!canvas || !form || !hiddenInput) return;

            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgba(255, 255, 255, 0)',
                penColor: 'rgb(17, 24, 39)',
            });

            // Signature type gating
            const typeRadios = form.querySelectorAll('input[name="signature_type"]');
            const qrWrapper = document.getElementById('qr-upload-wrapper');
            const handWrapper = document.getElementById('hand-signature-wrapper');
            const qrInput = form.querySelector('input[name="qr_image"]');

            function applySignatureType(){
                const selected = form.querySelector('input[name="signature_type"]:checked')?.value;
                const useHand = selected === 'hand';

                if(qrWrapper) qrWrapper.style.display = useHand ? 'none' : 'block';
                if(handWrapper) handWrapper.style.display = useHand ? 'block' : 'none';

                if(qrInput) {
                    qrInput.disabled = useHand;
                    if(useHand) qrInput.value = '';
                }

                if(!useHand){
                    // clear hand signature so backend doesn't persist wrong data
                    signaturePad.clear();
                    setSignatureData('');
                }
            }

            typeRadios.forEach(r => r.addEventListener('change', applySignatureType));
            applySignatureType();



            function resizeCanvas() {
                const data = signaturePad.toDataURL();
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                const width = 280;
                const height = 140;
                const ctx = canvas.getContext('2d');

                canvas.width = width * ratio;
                canvas.height = height * ratio;
                ctx.setTransform(1, 0, 0, 1, 0, 0);
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.scale(ratio, ratio);

                signaturePad.clear();
                if (data && data !== 'data:,') {
                    signaturePad.fromDataURL(data);
                }
            }

            function updatePreview(data) {
                if (!previewWrapper || !previewImage) return;
                if (data) {
                    previewImage.src = data;
                    previewWrapper.style.display = 'block';
                } else {
                    previewImage.src = '';
                    previewWrapper.style.display = 'none';
                }
            }

            function setSignatureData(data) {
                hiddenInput.value = data || '';
                updatePreview(data);
            }

            function refreshSignatureData() {
                if (signaturePad.isEmpty()) {
                    setSignatureData('');
                    return;
                }
                setSignatureData(signaturePad.toDataURL('image/png'));
            }

            window.addEventListener('resize', resizeCanvas);
            resizeCanvas();

            if (existingSignature) {
                signaturePad.fromDataURL(existingSignature);
                setSignatureData(existingSignature);
            } else {
                setSignatureData('');
            }

            canvas.addEventListener('pointerup', refreshSignatureData);
            canvas.addEventListener('pointercancel', refreshSignatureData);
            clearButton.addEventListener('click', () => {
                signaturePad.clear();
                setSignatureData('');
            });

            form.addEventListener('submit', () => {
                if (signaturePad.isEmpty()) {
                    hiddenInput.value = '';
                } else {
                    hiddenInput.value = signaturePad.toDataURL('image/png');
                }
            });
        })();
    </script>
</div>
