@props(['invoice' => null])

<div>
    <label>Nomor</label>
    <input name="nomor" value="{{ old('nomor', $invoice?->nomor) }}" required maxlength="255">
    @error('nomor') <div class="error">{{ $message }}</div> @enderror

    <label>Tanggal</label>
    <input type="date" name="tanggal" value="{{ old('tanggal', $invoice?->tanggal?->format('Y-m-d')) }}" required>
    @error('tanggal') <div class="error">{{ $message }}</div> @enderror

    <h3 class="section-title">Penjual</h3>

    <label>NPWP Penjual</label>
    <input name="npwp_penjual" value="{{ old('npwp_penjual', $invoice?->npwp_penjual) }}" maxlength="32">
    @error('npwp_penjual') <div class="error">{{ $message }}</div> @enderror

    <label>Nama Penjual</label>
    <input name="nama_penjual" value="{{ old('nama_penjual', $invoice?->nama_penjual) }}" maxlength="255">
    @error('nama_penjual') <div class="error">{{ $message }}</div> @enderror

    <label>Alamat Penjual</label>
    <textarea name="alamat_penjual" rows="3" maxlength="255">{{ old('alamat_penjual', $invoice?->alamat_penjual) }}</textarea>
    @error('alamat_penjual') <div class="error">{{ $message }}</div> @enderror

    <h3 class="section-title">Pembeli</h3>

    <label>NPWP Pembeli</label>
    <input name="npwp_pembeli" value="{{ old('npwp_pembeli', $invoice?->npwp_pembeli) }}" maxlength="32">
    @error('npwp_pembeli') <div class="error">{{ $message }}</div> @enderror

    <label>Nama Pembeli</label>
    <input name="nama_pembeli" value="{{ old('nama_pembeli', $invoice?->nama_pembeli) }}" maxlength="255">
    @error('nama_pembeli') <div class="error">{{ $message }}</div> @enderror

    <label>Alamat Pembeli</label>
    <textarea name="alamat_pembeli" rows="3" maxlength="255">{{ old('alamat_pembeli', $invoice?->alamat_pembeli) }}</textarea>
    @error('alamat_pembeli') <div class="error">{{ $message }}</div> @enderror


    @include('invoices.components.items', ['oldItems' => old('items', $invoice?->items?->toArray() ?? [])])


    {{-- total tetap dihitung backend, tapi input ini dipakai untuk menampilkan angka (opsional) --}}
    <label>Total</label>
    <input name="total" id="invoice-total" type="number" step="0.01" value="{{ old('total', $invoice?->total ?? 0) }}" required readonly>
    @error('total') <div class="error">{{ $message }}</div> @enderror

    <!-- <h3 style="margin:16px 0 8px;">Role Penandatangan</h3> -->

    <label>Pihak Penandatangan (contoh: Admin)</label>
    <input name="role_penandatangan" value="{{ old('role_penandatangan', $invoice?->role_penandatangan ?? '') }}" maxlength="255">
    @error('role_penandatangan') <div class="error">{{ $message }}</div> @enderror


    <label>Mata Uang (currency)</label>
    <input name="currency" value="{{ old('currency', $invoice?->currency ?? 'IDR') }}" maxlength="3">
    @error('currency') <div class="error">{{ $message }}</div> @enderror

    <input type="hidden" name="signature_data" id="signature_data" value="{{ old('signature_data', $invoice?->signature_data) }}">

    <div id="signature-pad-wrapper">
        <label>Signature Digital</label>
        <div class="signature-card">
            <canvas id="signature-canvas" width="280" height="140"></canvas>
            <div class="signature-pad-actions">
                <button type="button" class="btn btn-secondary" id="clear-signature">Clear Signature</button>
            </div>
            <div class="signature-pad-info">Gunakan mouse atau sentuhan untuk menggambar tanda tangan. Kosongkan jika tidak ingin merubah tanda tangan saat edit.</div>
        </div>
        <div id="signature-preview-wrapper" class="invoice-signature-preview-wrapper">
            <label>Preview Tanda Tangan</label>
            <img id="signature-preview" src="" alt="Preview Tanda Tangan" class="invoice-signature-preview-image" />
        </div>
        <label class="signature-name-label">Nama Penandatangan</label>
        <input type="text" name="signature_name" id="signature_name" value="{{ old('signature_name', $invoice?->signature_name) }}" maxlength="255">
        @error('signature_name') <div class="error">{{ $message }}</div> @enderror
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


