@extends('layouts.app')

@section('content')
<style>
    .weights-card {
        background: white;
        padding: 40px 30px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .table th {
        background: #f8f9fa;
    }

    .btn-success {
        border-radius: 8px;
        font-weight: 600;
    }
</style>

<div class="container mt-5">
    <div class="weights-card">
        <h3 class="fw-bold mb-4 text-center">⚖️ Penentuan Bobot & Jenis Kriteria</h3>

        <form method="POST" action="{{ route('decision.calculate', $session->id) }}">
            @csrf

            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead>
                        <tr>
                            <th>Kriteria</th>
                            <th>Tipe</th>
                            <th>Bobot</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($criteria as $c)
                            <tr>
                                <td>{{ $c->name }}</td>
                                <td>
                                    <select name="types[{{ $c->id }}]" class="form-select">
                                        <option value="benefit" {{ $c->type == 'benefit' ? 'selected' : '' }}>Benefit</option>
                                        <option value="cost" {{ $c->type == 'cost' ? 'selected' : '' }}>Cost</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" max="1" name="weights[{{ $c->id }}]"
                                           value="{{ $c->weight }}" class="form-control" required>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success px-4 py-2">💡 Hitung Menggunakan TOPSIS</button>
            </div>
        </form>
    </div>
</div>
@endsection
