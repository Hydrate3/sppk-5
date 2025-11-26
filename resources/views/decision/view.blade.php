@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #e7eeef;
    }

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        margin-bottom: 30px;
        padding-top: 20px;
    }

    .page-header h2 {
        font-weight: 700;
        color: #222;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .table-section {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-top: 20px;
    }

    .btn-success {
        background-color: #2ba7ba;
        border: none;
        font-weight: 600;
        border-radius: 6px;
    }

    .btn-success:hover {
        background-color: #1f8f9e;
    }

    .btn-secondary {
        border-radius: 6px;
    }

    table {
        background: white;
        border-radius: 10px;
        overflow: hidden;
    }
</style>

<div class="container mt-4">
    <!-- Header -->
    <div class="page-header">
        <h2>📊 Data Alternatif - {{ $session->name }}</h2>
        <div>
            <a href="{{ route('decision.weights', $session->id) }}" class="btn btn-success me-2">
                Lanjutkan ke Penentuan Bobot →
            </a>
            <a href="{{ route('decision.history') }}" class="btn btn-secondary">
                ← Kembali ke Riwayat
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="table-section">
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nama Produk</th>
                        @foreach($criteria as $c)
                            <th>{{ $c->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($alternatives as $a)
                        <tr>
                            <td>{{ $a->name }}</td>
                            @foreach($criteria as $c)
                                @php
                                    $val = $a->evaluations->firstWhere('criterion_id', $c->id)?->value ?? '-';
                                @endphp
                                <td>{{ $val }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
