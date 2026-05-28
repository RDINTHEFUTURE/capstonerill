<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Invoice</title>
    <link rel="stylesheet" href="{{ asset('css/invoice-edit.css') }}">
</head>
<body class="invoice-edit-page">
    <h1>Edit Invoice</h1>

    <div class="card">
        <form method="POST" action="{{ route('invoices.update', $invoice) }}">
            @csrf
            @method('PUT')

            <x-invoices.form :invoice="$invoice" />

            <div class="form-actions-wrapper">

                <div class="actions">
                    <button class="btn" type="submit">Simpan Perubahan & Update QR</button>
                    <a class="btn btn-secondary" href="{{ route('invoices.show', $invoice) }}">Batal</a>
                </div>

        </form>
    </div>
</body>
</html>

