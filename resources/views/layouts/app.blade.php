<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ระบบจองห้อง')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #f4f7f6;
        }

        .navbar-nav .nav-link {
    font-size: 1.1rem;
    font-weight: 500;
}

.navbar-nav .nav-link:hover {
    color: #ffcc00;
    text-decoration: underline;
}


        .container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        footer {
            background-color: #343a40;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            font-size: 0.9rem;
            margin-top: 30px;
        }

        footer a {
            color: #f8f9fa;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        .btn-outline-primary {
            border-radius: 30px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <!-- Logo หรือชื่อแบรนด์ -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('rooms.index') }}"> 
            <strong>ระบบจองห้อง</strong>
        </a>

        <!-- ปุ่ม toggle สำหรับขนาดหน้าจอเล็ก -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- เมนูหลัก -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('rooms.index') }}">หน้าแรก</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="">เกี่ยวกับเรา</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="">ติดต่อเรา</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

    
    <div class="container mt-4">
        @yield('content')
    </div>

    <footer>
        <p>&copy; {{ date('Y') }} ระบบจองห้อง. All rights reserved.</p>
        <p>Designed by <a href="https://www.example.com" target="_blank">Your Company</a></p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
