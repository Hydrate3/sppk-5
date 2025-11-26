@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #e7eeef;
    }

    .upload-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        padding: 60px 0;
    }

    .hero-text {
        flex: 1;
        min-width: 300px;
    }

    .hero-text h2 {
        font-weight: 700;
        font-size: 1.8rem;
        color: #222;
    }

    .hero-text p {
        color: #333;
        font-size: 1rem;
    }

    .hero-text .badge-free {
        background: #ffc107;
        font-weight: 600;
        border-radius: 8px;
        padding: 4px 8px;
    }

    .upload-card {
        flex: 1;
        background: #cfd1d3;
        border-radius: 12px;
        padding: 40px 30px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        text-align: center;
        min-width: 300px;
    }

    .btn-upload {
        background-color: #2ba7ba;
        border: none;
        color: #fff;
        font-weight: 600;
        padding: 10px 30px;
        border-radius: 6px;
    }

    .btn-upload:hover {
        background-color: #2491a1;
    }
</style>

<div class="container mt-5">
    <div class="upload-section">
        <div class="hero-text">
            <h2>Sistem Pengadaan & Keputusan Barang</h2>
            <p>100% Otomatis dan <span class="badge-free">Gratis</span></p>
        </div>

        <div class="upload-card">
            <form method="POST" action="{{ route('decision.upload') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3 text-start">
                    <label class="form-label fw-semibold">Nama Keputusan</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Pemilihan Supplier" required>
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-semibold">File Excel</label>
                    <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                </div>

                <button type="submit" class="btn btn-upload mt-3">Upload & Proses</button>
            </form>

            <p class="mt-3 fw-semibold">atau drop file (.xlsx)</p>
        </div>
    </div>
</div>
@endsection
