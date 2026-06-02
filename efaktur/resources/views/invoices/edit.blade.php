@extends('layouts.mazer')

@section('title', 'Edit Invoice')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/invoice-edit.css') }}">
@endpush

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>Edit Invoice</h3>
        <a class="btn btn-secondary" href="{{ route('invoices.show', $invoice) }}">Batal</a>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <form method="POST" action="{{ route('invoices.update', $invoice) }}">
                @csrf
                @method('PUT')

                <x-invoices.form :invoice="$invoice" :chartOfAccounts="$chartOfAccounts" />

                <div class="mt-3">
                    <button class="btn btn-primary" type="submit">Simpan Perubahan & Update QR</button>
                </div>
            </form>
        </div>
    </div>
@endsection
