@props(['invoice' => null])

<div>
    <label>Nomor</label>
    <input name="nomor" value="{{ old('nomor', $invoice?->nomor) }}" required maxlength="255">
    @error('nomor') <div class="error">{{ $message }}</div> @enderror

    <label>Tanggal</label>
    <input type="date" name="tanggal" value="{{ old('tanggal', $invoice?->tanggal?->format('Y-m-d')) }}" required>
    @error('tanggal') <div class="error">{{ $message }}</div> @enderror

    <h3 style="margin:16px 0 8px;">Penjual</h3>

    <label>NPWP Penjual</label>
    <input name="npwp_penjual" value="{{ old('npwp_penjual', $invoice?->npwp_penjual) }}" maxlength="32">
    @error('npwp_penjual') <div class="error">{{ $message }}</div> @enderror

    <label>Nama Penjual</label>
    <input name="nama_penjual" value="{{ old('nama_penjual', $invoice?->nama_penjual) }}" maxlength="255">
    @error('nama_penjual') <div class="error">{{ $message }}</div> @enderror

    <label>Alamat Penjual</label>
    <textarea name="alamat_penjual" rows="3" maxlength="255">{{ old('alamat_penjual', $invoice?->alamat_penjual) }}</textarea>
    @error('alamat_penjual') <div class="error">{{ $message }}</div> @enderror

    <h3 style="margin:16px 0 8px;">Pembeli</h3>

    <label>NPWP Pembeli</label>
    <input name="npwp_pembeli" value="{{ old('npwp_pembeli', $invoice?->npwp_pembeli) }}" maxlength="32">
    @error('npwp_pembeli') <div class="error">{{ $message }}</div> @enderror

    <label>Nama Pembeli</label>
    <input name="nama_pembeli" value="{{ old('nama_pembeli', $invoice?->nama_pembeli) }}" maxlength="255">
    @error('nama_pembeli') <div class="error">{{ $message }}</div> @enderror

    <label>Alamat Pembeli</label>
    <textarea name="alamat_pembeli" rows="3" maxlength="255">{{ old('alamat_pembeli', $invoice?->alamat_pembeli) }}</textarea>
    @error('alamat_pembeli') <div class="error">{{ $message }}</div> @enderror


    @include('invoices.components.items', ['oldItems' => old('items', [])])


    {{-- total tetap dihitung backend, tapi input ini dipakai untuk menampilkan angka (opsional) --}}
    <label>Total</label>
    <input name="total" id="invoice-total" type="number" step="0.01" value="{{ old('total', $invoice?->total ?? 0) }}" required readonly>
    @error('total') <div class="error">{{ $message }}</div> @enderror

    <h3 style="margin:16px 0 8px;">Direktur / Pejabat yang Ditunjuk</h3>

    <label>Nama Pejabat</label>
    <input name="pejabat" value="{{ old('pejabat', $invoice?->pejabat ?? '') }}" maxlength="255">
    @error('pejabat') <div class="error">{{ $message }}</div> @enderror

    <label>Mata Uang (currency)</label>
    <input name="currency" value="{{ old('currency', $invoice?->currency ?? 'IDR') }}" maxlength="3">
    @error('currency') <div class="error">{{ $message }}</div> @enderror


    <style>
        /* Benahi overflow: pastikan kolom grid items muat di dalam card */
        #items-container { max-width: 100%; width:100%; }
        .item-row input { width: 100%; box-sizing: border-box; }
        .item-row { grid-template-columns: 2.2fr 1.1fr 1.6fr 1.6fr 1fr !important; }
        /* paksa grid item untuk tidak “keluar” */
        .item-row > div { min-width: 0; }
    </style>


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
    </script>
</div>


