@extends('layouts.mazer')

@section('title', 'Buat Invoice')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/invoice-create.css') }}">
@endpush

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>Buat Invoice</h3>
        <a class="btn btn-secondary" href="{{ route('invoices.index') }}">Kembali</a>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <form method="POST" action="{{ route('invoices.store') }}">
                @csrf

                <x-invoices.form :chartOfAccounts="$chartOfAccounts" />

                <div class="mt-3">
                    <button class="btn btn-primary" type="submit">Simpan & Buat QR</button>
                </div>
            </form>
        </div>
    </div>
@endsection
