@extends('layouts.mazer')

@section('title', 'Import Data')

@section('content')
    <div class="page-heading">
        <h3>Import Data</h3>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('errors') && count(session('errors')))
        <div class="alert alert-warning">
            <strong>Peringatan:</strong>
            <ul class="mb-0 mt-1">
                @foreach(session('errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Import Invoice</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Format CSV dengan kolom: <code>Nomor, Tanggal, Penjual, Pembeli, Total, Currency</code></p>
                    <form method="POST" action="{{ route('reports.import-invoices') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="form-label">Pilih File CSV</label>
                            <input type="file" name="csv_file" class="form-control" accept=".csv,.txt" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Import Invoice</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Import Chart of Accounts</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Format CSV dengan kolom: <code>Kode Akun, Nama Akun, Kode Lama 1, Kode Lama 2, Header, Tipe</code></p>
                    <form method="POST" action="{{ route('reports.import-coa') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="form-label">Pilih File CSV</label>
                            <input type="file" name="csv_file" class="form-control" accept=".csv,.txt" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Import Akun</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Template Download</h5>
        </div>
        <div class="card-body">
            <p class="text-muted">Download template CSV untuk import:</p>
            <a href="{{ route('reports.template-invoices') }}" class="btn btn-outline-secondary btn-sm me-2">Template Invoice</a>
            <a href="{{ route('reports.template-coa') }}" class="btn btn-outline-secondary btn-sm">Template Chart of Accounts</a>
        </div>
    </div>
@endsection
