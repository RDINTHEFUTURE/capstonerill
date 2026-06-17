@extends('layouts.mazer')

@section('title', 'Aktivitas')

@section('content')
    <div class="page-heading">
        <h3>Log Aktivitas</h3>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Pengguna</th>
                            <th>Aksi</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                            <tr>
                                <td>{{ $activity->created_at->format('d M Y H:i') }}</td>
                                <td>{{ $activity->user?->name ?? 'System' }}</td>
                                <td>
                                    @if($activity->action === 'created')
                                        <span class="badge bg-success">Dibuat</span>
                                    @elseif($activity->action === 'updated')
                                        <span class="badge bg-primary">Diperbarui</span>
                                    @elseif($activity->action === 'deleted')
                                        <span class="badge bg-danger">Dihapus</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $activity->action }}</span>
                                    @endif
                                </td>
                                <td>{{ $activity->description }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center">Belum ada aktivitas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $activities->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
