<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Invoice</title>
    <link rel="stylesheet" href="{{ asset('css/invoice-create.css') }}">
</head>
<body class="invoice-create-page">
    <h1>Buat Invoice</h1>

    <div class="card">
        <form method="POST" action="{{ route('invoices.store') }}">
            @csrf

            <x-invoices.form />

            <div class="actions">
                <button class="btn" type="submit">Simpan & Buat QR</button>
                <a class="btn btn-secondary" href="{{ route('invoices.index') }}">Kembali</a>
            </div>
        </form>
    </div>
</body>
</html>

