<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGADA | Sistem Keputusan Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --navbar-bg: #a7c6e8;
            --accent-color: #2ba7ba;
        }

        body {
            background-color: #dfe6e5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Navbar */
        .navbar {
            background-color: var(--navbar-bg);
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 700;
            color: #000 !important;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-brand img {
            width: 26px;
        }

        .nav-link {
            color: #000 !important;
            font-weight: 600;
            margin-right: 20px;
        }

        .nav-link.active, .nav-link:hover {
            color: var(--accent-color) !important;
        }

        main {
            min-height: calc(100vh - 70px);
            padding-top: 50px;
        }
    </style>
</head>

<body>
    <!-- 🌐 Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('decision.index') }}">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="logo">
                NGADA
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('decision.index') ? 'active' : '' }}" href="{{ route('decision.index') }}">Upload</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('decision.weights') ? 'active' : '' }}" href="{{ route('decision.history') }}">Weight</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('decision.history') ? 'active' : '' }}" href="{{ route('decision.history') }}">History</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
