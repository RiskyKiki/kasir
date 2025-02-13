<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>

    <!-- Bootstrap & FontAwesome -->
    <link rel="stylesheet" href="{{ asset('modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('modules/fontawesome/css/all.min.css') }}">
    
    <!-- iziToast -->
    <link rel="stylesheet" href="{{ asset('modules/izitoast/css/iziToast.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body {
            background: url('{{ asset('img/avatar/avatar-1.png') }}');
        }
    </style>
</head>
<body>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-12 col-md-6 offset-md-3 col-lg-4 offset-lg-4">
                        <div class="card card-primary">
                            <div class="card-header text-center">
                                <h4>Login</h4>
                            </div> 
                            <div class="card-body">
                                <form id="loginForm" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}"  autofocus>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input id="password" type="password" class="form-control" name="password" >
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" id="loginButton" class="btn btn-primary btn-lg btn-block">
                                            Login
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- jQuery & Bootstrap -->
    <script src="{{ asset('modules/jquery.min.js') }}"></script>
    <script src="{{ asset('modules/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- iziToast -->
    <script src="{{ asset('modules/izitoast/js/iziToast.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#loginForm').submit(function(event) {
                event.preventDefault(); // Mencegah submit form langsung

                let email = $('#email').val().trim();
                let password = $('#password').val().trim();

                // 1. Menekan tombol login tanpa memasukkan email & password
                if (email === '' && password === '') {
                    iziToast.warning({ title: 'Peringatan', message: 'Email & Password wajib diisi!', position: 'topCenter' });
                    return;
                }

                // 2. Memasukkan format non-email pada kolom email
                let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (email !== '' && !emailPattern.test(email)) {
                    iziToast.error({ title: 'Kesalahan', message: 'Format email tidak valid!', position: 'topCenter' });
                    return;
                }

                // 3. Hanya mengisi salah satu dari email atau password
                if (email === '' || password === '') {
                    iziToast.warning({ title: 'Kesalahan', message: 'Email & Password wajib diisi!', position: 'topCenter' });
                    return;
                }

                // Jika semua validasi lolos, submit form
                this.submit();
            });

            // Menampilkan notifikasi dari Laravel session
            @if(session('success'))
                iziToast.success({ title: 'Sukses', message: '{{ session('success') }}', position: 'topRight' });
            @endif

            @if(session('error'))
                iziToast.error({ title: 'Gagal', message: '{{ session('error') }}', position: 'topCenter' });
            @endif

            @if(session('info'))
                iziToast.info({ title: 'Info', message: '{{ session('info') }}', position: 'topRight' });
            @endif
        });
    </script>
</body>
</html>
