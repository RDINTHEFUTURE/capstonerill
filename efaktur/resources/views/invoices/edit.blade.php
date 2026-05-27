<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Invoice</title>
    <style>
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin: 24px; max-width: 900px; }
        .btn { display: inline-block; padding: 10px 14px; background: #2563eb; color: #fff; text-decoration: none; border-radius: 8px; border: none; cursor: pointer; }
        .btn-secondary { background: #6b7280; }
        .card { border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; }
        label { display:block; margin-top: 10px; font-weight: 600; }
        input, textarea, select { width: 100%; padding: 10px; margin-top: 6px; border: 1px solid #d1d5db; border-radius: 8px; }
        .error { color: #b91c1c; margin-top: 8px; }
        .actions { display:flex; gap: 10px; margin-top: 16px; }
    </style>
</head>
<body>
    <h1>Edit Invoice</h1>

    <div class="card">
        <form method="POST" action="{{ route('invoices.update', $invoice) }}">
            @csrf
            @method('PUT')

            <x-invoices.form :invoice="$invoice" />

            <div style="overflow:hidden;">

                <div class="actions">
                    <button class="btn" type="submit">Simpan Perubahan & Update QR</button>
                    <a class="btn btn-secondary" href="{{ route('invoices.show', $invoice) }}">Batal</a>
                </div>

        </form>
    </div>
</body>
</html>

