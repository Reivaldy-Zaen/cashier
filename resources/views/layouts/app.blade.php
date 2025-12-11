<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Halaman Utama')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('school.png') }}">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        #wrapper {
            display: flex;
            min-height: 100vh;
            background-color: #f8f9fa;
            /* Tambahkan transisi untuk efek slide */
            transition: all 0.3s ease;
        }
        #sidebar-wrapper {
            width: 250px;
            min-width: 250px;
            background-color: #343a40;
            color: #fff;
            padding-top: 1rem;
            box-shadow: 2px 0 5px rgba(0,0,0,.1);
            transition: all 0.3s ease; /* Transisi untuk sidebar itu sendiri */
        }

        /* Gaya saat sidebar diciutkan */
        #wrapper.toggled #sidebar-wrapper {
            margin-left: -250px; /* Sembunyikan sidebar di luar layar */
        }
        #wrapper.toggled #page-content-wrapper {
            width: 100%; /* Konten mengisi penuh lebar */
            margin-left: 0; /* Pastikan tidak ada margin sisa dari sidebar */
        }

        #page-content-wrapper {
            flex-grow: 1;
            padding: 1.5rem;
            width: calc(100% - 250px);
            transition: all 0.3s ease; /* Transisi untuk konten saat sidebar toggled */
        }
        .sidebar-heading {
            padding: 0.875rem 1.25rem;
            font-size: 1.3rem;
            font-weight: bold;
            color: #fff;
            margin-bottom: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,.1);
            padding-bottom: 1rem;
        }
        .list-group-item {
            background-color: transparent;
            border: none;
            color: rgba(255,255,255,.7);
            padding: 0.75rem 1.25rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            border-radius: 0;
            white-space: nowrap; /* Mencegah teks melipat saat sidebar diciutkan (jika sidebar tidak disembunyikan sepenuhnya) */
            overflow: hidden; /* Sembunyikan overflow saat diciutkan */
        }
        .list-group-item i {
            margin-right: 0.8rem;
            font-size: 1.1rem;
        }
        .list-group-item:hover, .list-group-item:focus {
            color: #fff;
            background-color: rgba(255,255,255,.15);
        }
        .list-group-item.active {
            color: #fff;
            background-color: #0d6efd;
            font-weight: bold;
            border-left: 5px solid #0dcaf0;
            padding-left: calc(1.25rem - 5px);
        }
        .dropdown-menu {
            background-color: #495057;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
        }
        .dropdown-item {
            color: rgba(255,255,255,.8);
            padding: 0.5rem 1.25rem;
            transition: all 0.3s ease;
        }
        .dropdown-item:hover {
            background-color: rgba(255,255,255,.1);
            color: #fff;
        }
        .btn-sidebar-bottom {
            margin: 2rem 1.25rem 1rem;
            width: calc(100% - 2.5rem);
            text-align: center;
            padding: 0.75rem 1.25rem;
        }
        .btn-sidebar-bottom i {
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>

    <div class="d-flex" id="wrapper">
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">
                <i class="bi bi-gem me-2"></i> Family Cell
            </div>
            <div class="list-group list-group-flush">
                @if (Auth::user()->role === 'admin')
                <a href="{{ route('dashboard', ['userId' => Auth::user()->userId]) }}" 
                   class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door-fill"></i>Beranda
                </a>    
                @endif
                <a href="{{ route('product.index',['userId' =>Auth::user()->userId]) }}" 
                   class="list-group-item list-group-item-action {{ request()->routeIs('product.index') ? 'active' : '' }}">
                    <i class="bi bi-star-fill"></i>Product
                </a>
                @if (Auth::user()->role === 'admin')
                <a href="{{ route('kategori.index', ['userId'=>Auth::user()->userId]) }}" 
                   class="list-group-item list-group-item-action {{ request()->routeIs('kategori.index') ? 'active' : '' }}">
                    <i class="bi bi-tags-fill"></i>Kategori
                </a>
                @endif
                <a href="{{ route('transaction.index', ['userId' =>Auth::user()->userId])}}"
                   class="list-group-item list-group-item-action {{ request()->routeIs('transaction.index') ? 'active' : '' }}">
                    <i class="bi bi-cart"></i>transaction
                </a>
                @if (Auth::user()->role === 'admin')
                <a href="{{ route('transaction.history', ['userId' =>Auth::user()->userId])}}"
                   class="list-group-item list-group-item-action {{ request()->routeIs('transaction.history') ? 'active' : '' }}">
                    <i class="bi bi-cart"></i>transaction History
                </a>
                <a href="{{ route('admin.users.index') }}"
                class="list-group-item list-group-item-action {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Kelola Akun
                </a>    
                @endif
                {{-- <a class="btn btn-danger text-white btn-sidebar-bottom" href="#"><i class="bi bi-person-plus-fill"></i>Daftar</a> --}}
            </div>
        </div>
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4 shadow-sm rounded">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <span class="navbar-brand mb-0 h1 ms-3">@yield('title', 'Halaman Utama')</span>
                    <div class="ms-auto">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name ?? 'Guest' }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                                <li>
                                    <form id="logout-form-navbar" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form-navbar').submit();">
                                        Keluar
                                    </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="content-wrapper">
                @yield('nav') 
            </div>
        </div>
        </div>
    @yield('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.getElementById('wrapper').classList.toggle('toggled');
        });
    </script>

</body>
</html>