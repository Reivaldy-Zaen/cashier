<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Buat Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        /* Background dengan overlay */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1528698827591-e19ccd7bc23d?q=80&w=2076') center/cover no-repeat;
            filter: brightness(0.4);
            z-index: -1;
        }

        /* Overlay gradien biru */
        body::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.6), rgba(25, 135, 84, 0.4));
            z-index: -1;
        }

        .admin-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .admin-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            border: none;
            animation: slideIn 0.6s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            padding: 30px 20px;
            border: none;
            position: relative;
        }

        .card-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
        }

        .card-header h4 {
            font-weight: 600;
            letter-spacing: 0.5px;
            margin: 0;
            color: white;
        }

        .card-body {
            padding: 35px 30px;
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
            background-color: #f8f9ff;
        }

        .form-control:hover {
            border-color: #0d6efd;
        }

        .mb-3 {
            margin-bottom: 1.5rem;
        }

        .btn-success {
            background: linear-gradient(135deg, #198754 0%, #146c43 100%);
            border: none;
            border-radius: 10px;
            padding: 13px;
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(25, 135, 84, 0.3);
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #146c43 0%, #0d5132 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(25, 135, 84, 0.4);
        }

        .btn-success:active {
            transform: translateY(0);
        }

        /* Alert styling */
        .alert-danger {
            background: linear-gradient(135deg, #dc3545 0%, #bb2d3b 100%);
            border: none;
            border-radius: 10px;
            color: white;
            padding: 15px;
            margin-bottom: 20px;
            animation: slideDown 0.4s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger ul {
            margin: 0;
            padding-left: 20px;
        }

        .alert-danger li {
            margin: 5px 0;
        }

        /* Link styling */
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            color: #0d6efd;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 8px;
        }

        .back-link:hover {
            color: #0a58ca;
            background: rgba(13, 110, 253, 0.1);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .admin-container {
                padding: 15px;
            }
            
            .card-body {
                padding: 25px 20px;
            }
            
            .card-header {
                padding: 25px 15px;
            }
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <div class="admin-card card">
            <div class="card-header text-center">
                <h4>
                    <i class="bi bi-person-plus-fill me-2"></i>Buat Akun Admin
                </h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Terjadi Kesalahan:</strong>
                        <ul style="margin-top: 10px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-person-circle me-1"></i>Nama Admin
                        </label>
                        <input type="text" class="form-control" name="name" required 
                            placeholder="Masukkan nama admin" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-envelope-fill me-1"></i>Email Admin
                        </label>
                        <input type="email" class="form-control" name="email" required 
                            placeholder="Masukkan email admin" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-lock-fill me-1"></i>Password Admin
                        </label>
                        <input type="password" class="form-control" name="password" required 
                            placeholder="Masukkan password" />
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-save-fill me-2"></i>Simpan Admin
                        </button>
                    </div>
                </form>

                <a href="{{ route('login') }}" class="back-link">
                    <i class="bi bi-arrow-left-circle me-1"></i>Kembali ke Login
                </a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
</body>

</html>