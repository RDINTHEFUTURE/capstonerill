@extends('layouts.mazer')

@section('title', 'Daftar Invoice')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/invoice-index.css') }}">
@endpush

@section('content')
    <div class="page-heading d-flex justify-content-between align-items-center motion-safe:animate-slideUp">
        <h3>Daftar Invoice</h3>
        <a class="btn btn-primary hover:-translate-y-0.5 hover:shadow-lg active:scale-95 transition-all duration-200" href="{{ route('invoices.create') }}">+ Buat Invoice</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success motion-safe:animate-slideDown">{{ session('success') }}</div>
    @endif

    <div class="card mb-3 hover:shadow-md transition-shadow duration-200">
        <div class="card-body">
            <form method="GET" action="{{ route('invoices.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Cari Nomor/Nama</label>
                    <input type="text" name="search" class="form-control transition-all duration-200 focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400" placeholder="Nomor atau nama..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select transition-all duration-200 focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400">
                        <option value="">Semua</option>
                        <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Approval</label>
                    <select name="approval_status" class="form-select transition-all duration-200 focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400">
                        <option value="">Semua</option>
                        <option value="pending_review" {{ request('approval_status') === 'pending_review' ? 'selected' : '' }}>Menunggu Review</option>
                        <option value="revision_needed" {{ request('approval_status') === 'revision_needed' ? 'selected' : '' }}>Perlu Revisi</option>
                        <option value="approved" {{ request('approval_status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="from" class="form-control transition-all duration-200 focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400" value="{{ request('from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="to" class="form-control transition-all duration-200 focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400" value="{{ request('to') }}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100 hover:-translate-y-0.5 hover:shadow-md active:scale-95 transition-all duration-200">Cari</button>
                </div>
                @if(request('search') || request('status') || request('approval_status') || request('from') || request('to'))
                    <div class="col-md-1">
                        <a href="{{ route('invoices.index') }}" class="btn btn-secondary w-100 hover:-translate-y-0.5 hover:shadow-md active:scale-95 transition-all duration-200">Reset</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card hover:shadow-md transition-shadow duration-200">
        <div class="card-body">
            <form method="POST" action="{{ route('invoices.bulk-action') }}" id="bulk-form">
                @csrf
                <div class="d-flex gap-2 mb-3">
                    <select name="action" class="form-select transition-all duration-200 focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400" style="width: auto;" required>
                        <option value="">Pilih Aksi</option>
                        <option value="paid">Tandai Lunas</option>
                        <option value="unpaid">Tandai Belum Lunas</option>
                        <option value="delete">Hapus</option>
                    </select>
                    <button type="submit" class="btn btn-primary hover:-translate-y-0.5 hover:shadow-md active:scale-95 transition-all duration-200" onclick="return confirm('Proses terpilih?')">Terapkan</button>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>Nomor Seri</th>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Total</th>
                            <th>Dibuat Oleh</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($invoices as $inv)
                            <tr class="transition-colors duration-150 hover:bg-indigo-50/50 dark:hover:bg-indigo-900/20">
                                <td><input type="checkbox" name="ids[]" value="{{ $inv->id }}" class="invoice-checkbox"></td>
                                <td>{{ $inv->nomor }}</td>
                            <td>{{ $inv->tanggal->format('Y-m-d') }}</td>
                            <td>{{ $inv->nama_penjual ?? '-' }}</td>
                            <td>{{ number_format((float)$inv->total, 2, ',', '.') }} {{ $inv->currency }}</td>
                            <td>{{ $inv->creator->name ?? '-' }}</td>
                            <td>
                                @if($inv->isPaid())
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-warning text-dark animate-pulse">Belum Lunas</span>
                                @endif
                                @if($inv->isPendingReview())
                                    <span class="badge bg-info ms-1 animate-pulse">Review</span>
                                @elseif($inv->isRevisionNeeded())
                                    <span class="badge bg-danger ms-1">Revisi</span>
                                @elseif($inv->isApproved())
                                    <span class="badge bg-success ms-1">Disetujui</span>
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-sm btn-outline-primary hover:-translate-y-0.5 hover:shadow-md active:scale-95 transition-all duration-200" href="{{ route('invoices.show', $inv) }}">Detail</a>
                                @if($inv->isPendingReview() && auth()->user()->roleLevel() >= 2)
                                    <a class="btn btn-sm btn-info text-white hover:-translate-y-0.5 hover:shadow-md active:scale-95 transition-all duration-200" href="{{ route('invoices.review', $inv) }}">Review</a>
                                @endif
                                @if($inv->isPendingReview() || $inv->isRevisionNeeded())
                                    <a class="btn btn-sm btn-outline-secondary hover:-translate-y-0.5 hover:shadow-md active:scale-95 transition-all duration-200" href="{{ route('invoices.edit', $inv) }}">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8">Belum ada data.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $invoices->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('select-all')?.addEventListener('change', function() {
            document.querySelectorAll('.invoice-checkbox').forEach(cb => cb.checked = this.checked);
        });
    </script>
    @endpush
@endsection
