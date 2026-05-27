<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Invoice</title>
    <style>
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin: 24px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f6f6f6; }
        .row { display: flex; justify-content: space-between; align-items: center; }
        .btn { display: inline-block; padding: 10px 14px; background: #2563eb; color: #fff; text-decoration: none; border-radius: 8px; }
        .btn-secondary { background: #6b7280; }
        .success { background: #dcfce7; border: 1px solid #86efac; padding: 10px; border-radius: 8px; margin-top: 12px; }
    </style>
</head>
<body>
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
                    <a class="btn" href="{{ route('invoices.edit', $inv) }}" style="margin-left:8px;">Edit</a>
                </td>

            </tr>
        @empty
            <tr><td colspan="5">Belum ada data.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div style="margin-top: 16px;">
        {{ $invoices->links() }}
    </div>
</body>
</html>

