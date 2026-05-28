<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Invoice</title>
    <link rel="stylesheet" href="{{ asset('css/invoice-index.css') }}">
</head>
<body class="invoice-index-page">
    <div class="row">
        <h1>Daftar Invoice</h1>
        <a class="btn" href="{{ route('invoices.create') }}">+ Buat Invoice</a>
    </div>

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <table>
        <thead>
        <tr>
            <th>Nomor</th>
            <th>Tanggal</th>
            <th>Nama</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($invoices as $inv)
            <tr>
                <td>{{ $inv->nomor }}</td>
                <td>{{ $inv->tanggal->format('Y-m-d') }}</td>
                <td>{{ $inv->nama_penjual ?? '-' }}</td>

                <td>{{ number_format((float)$inv->total, 2, ',', '.') }} {{ $inv->currency }}</td>
                <td>
                    <a class="btn btn-secondary" href="{{ route('invoices.show', $inv) }}">Detail</a>
                    <a class="btn action-spaced" href="{{ route('invoices.edit', $inv) }}">Edit</a>
                </td>

            </tr>
        @empty
            <tr><td colspan="5">Belum ada data.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="pagination-container">
        {{ $invoices->links() }}
    </div>
</body>
</html>

