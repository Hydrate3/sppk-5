@extends('layouts.app')

@section('content')
<style>
    .results-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .table th {
        background: #f8f9fa;
    }

    .btn-secondary {
        border-radius: 6px;
        font-weight: 600;
    }
</style>

<div class="container mt-5">
    <div class="results-card">
        <h3 class="fw-bold mb-4 text-center">🏆 Hasil Perhitungan TOPSIS - {{ $session->name }}</h3>

        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Peringkat</th>
                        <th>Alternatif</th>
                        <th>Nilai Skor</th>
                    </tr>
                </thead>
                <tbody>
                    @php $rank = 1; @endphp
                    @foreach($scores as $altId => $score)
                        @php $alt = $alternatives->firstWhere('id', $altId); @endphp
                        <tr>
                            <td>{{ $rank++ }}</td>
                            <td>{{ $alt->name }}</td>
                            <td>{{ number_format($score, 4) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('decision.index') }}" class="btn btn-secondary px-4 py-2">← Kembali ke Upload</a>
        </div>
    </div>
</div>
@endsection
