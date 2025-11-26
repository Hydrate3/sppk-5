@extends('layouts.app')

@section('content')
<style>
    .history-container {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .table th {
        background-color: #f8f9fa;
    }

    .btn-primary, .btn-success {
        border-radius: 6px;
        font-weight: 600;
    }

    .btn-primary {
        background-color: #2ba7ba;
        border: none;
    }

    .btn-primary:hover {
        background-color: #1d8c9b;
    }
</style>

<div class="container mt-5">
    <div class="history-container">
        <h3 class="fw-bold mb-4">📜 Riwayat Perhitungan</h3>

        @if($sessions->isEmpty())
            <div class="alert alert-info">
                Belum ada sesi sebelumnya. Upload file Excel baru untuk memulai!
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Sesi</th>
                            <th>Dibuat Pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sessions as $index => $session)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $session->name }}</td>
                                <td>{{ $session->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('decision.view', $session->id) }}" class="btn btn-sm btn-primary">Lihat Data</a>
                                    <a href="{{ route('decision.weights', $session->id) }}" class="btn btn-sm btn-success">Hitung Ulang</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="text-end mt-4">
            <a href="{{ route('decision.index') }}" class="btn btn-outline-secondary">➕ Sesi Baru</a>
        </div>
    </div>
</div>
@endsection
