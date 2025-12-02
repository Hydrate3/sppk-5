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

    .guideline {
        background: #f1f3f5;
        border-left: 5px solid #198754;
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 0.95rem;
        color: #333;
        margin-bottom: 20px;
    }

    .total-weight-box {
        font-size: 1rem;
        text-align: right;
        margin-top: 10px;
        padding: 10px;
        border-radius: 8px;
        background: #f8f9fa;
        font-weight: 600;
    }

    .total-valid {
        color: #198754;
    }

    .total-invalid {
        color: #dc3545;
    }

    .alert-msg {
        text-align: right;
        font-size: 0.9rem;
        margin-top: 5px;
        font-weight: 500;
    }

    .alert-danger {
        color: #dc3545;
    }

    .alert-success {
        color: #198754;
    }
</style>

<div class="container mt-5">
    <div class="weights-card">
        <h3 class="fw-bold mb-4 text-center">⚖️ Penentuan Bobot & Jenis Kriteria</h3>

        <div class="guideline">
            💡 <strong>Panduan:</strong> Gunakan tipe <strong>Cost</strong> jika nilai yang lebih kecil lebih baik (misal: harga, waktu).
            Gunakan tipe <strong>Benefit</strong> jika nilai yang lebih besar lebih baik (misal: kualitas, kecepatan).
            <br>
            Masukkan bobot dalam bentuk <strong>persentase (%)</strong> agar total seluruh kriteria mendekati 100%.
        </div>

        <form method="POST" action="{{ route('decision.calculate', $session->id) }}">
            @csrf

            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead>
                        <tr>
                            <th>Kriteria</th>
                            <th>Tipe</th>
                            <th>Bobot (%)</th>
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
                                    <input type="number" step="0.01" min="0" max="100"
                                           name="weights[{{ $c->id }}]"
                                           value="{{ $c->weight * 100 }}"
                                           class="form-control weight-input"
                                           required>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Total Bobot Display -->
            <div id="total-weight-display" class="total-weight-box total-invalid">
                Total Bobot: 0%
            </div>
            <div id="weight-alert" class="alert-msg alert-danger">
                Bobot tidak boleh kurang dari 100%.
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success px-4 py-2">
                    💡 Hitung Menggunakan TOPSIS
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.weight-input');
    const totalDisplay = document.getElementById('total-weight-display');
    const alertBox = document.getElementById('weight-alert');
    const form = document.querySelector('form');

    function updateTotal() {
        let total = 0;
        inputs.forEach(input => {
            const val = parseFloat(input.value) || 0;
            total += val;
        });

        totalDisplay.textContent = `Total Bobot: ${total.toFixed(2)}%`;

        // kondisi peringatan
        if (total < 99.5) {
            totalDisplay.classList.add('total-invalid');
            totalDisplay.classList.remove('total-valid');
            alertBox.textContent = 'Bobot tidak boleh kurang dari 100%.';
            alertBox.className = 'alert-msg alert-danger';
        } else if (total > 100.5) {
            totalDisplay.classList.add('total-invalid');
            totalDisplay.classList.remove('total-valid');
            alertBox.textContent = 'Bobot tidak boleh lebih dari 100%.';
            alertBox.className = 'alert-msg alert-danger';
        } else {
            totalDisplay.classList.add('total-valid');
            totalDisplay.classList.remove('total-invalid');
            alertBox.textContent = '✅ Total bobot sudah sesuai (100%).';
            alertBox.className = 'alert-msg alert-success';
        }
    }

    inputs.forEach(input => input.addEventListener('input', updateTotal));
    updateTotal();

    // Convert percentage to decimal before submit
    form.addEventListener('submit', function(e) {
        let total = 0;
        inputs.forEach(input => total += parseFloat(input.value) || 0);

        if (total < 99.5 || total > 100.5) {
            e.preventDefault();
            alert('Total bobot harus 100% sebelum melanjutkan perhitungan.');
            return false;
        }

        inputs.forEach(input => {
            input.value = (parseFloat(input.value) / 100).toFixed(4);
        });
    });
});
</script>
@endsection
