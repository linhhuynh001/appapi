
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Theme CSS -->
<link rel="stylesheet" href="{{ asset('public/assets/css/theme.min.css')}}">
<link rel="stylesheet" href="{{ asset('public/assets/css/sweetalert2.min.css' )}}?ver={{ time() }}">
<link href="{{ asset('public/assets/fonts/font.css' )}}?ver=<?= time() ?>" rel="stylesheet">
<title>Login Shoptify Dashboard</title>

<style>
    .color-red {
        color: #e70b0b;
        font-size: 13px;
        padding-top: 5px;
    }
    .toast.toast-success{
        background:#343a40;	
        opacity: 1!important;
        z-index: 9999;
    }

</style>
</head>

<body>

    <div class="container d-flex flex-column">
        <div class="row align-items-center justify-content-center g-0 min-vh-100">
            <div class="col-lg-5 col-md-8 py-8 py-xl-0">
                <!-- Card -->
                <div class="card shadow ">
                    <!-- Card body -->
                    <div class="card-body p-6">
                        <div class="mb-4">
                            <div class="text-center">
                                <a href="#">
                                    Shoptify
                                </a>
                            </div>
                        </div>
                        <!-- Form -->
                        <form method="POST" action="{{ route('login.submit') }}">
                            @csrf
                            <!-- Username -->
                            <div class="mb-3">
                                <label for="text" class="form-label">Email</label>
                                <input type="email" required class="form-control" name="email" placeholder="E-Mail Address" value="{{ old('email') }}">
                                @if($errors->has('email'))
                                    <div class="color-red">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" required id="password" class="form-control" name="password" placeholder="**************" value="{{ old('password') }}">
                                @if($errors->has('password'))
                                    <div class="color-red">{{ $errors->first('password') }}</div>
                                @endif
                            </div>
                            <!-- Checkbox -->
                            <div>
                                <!-- Button -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary yellow">Login</button>
                                </div>
                            </div>
                            <hr class="my-4">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
  


  <!-- Scripts -->
  <!-- Libs JS -->
<script src="{{ asset('public/assets/libs/jquery/dist/jquery.min.js')}}"></script>
<script src="{{ asset('public/assets/js/theme.min.js')}}"></script>
<script src="{{ asset('public/assets/js/sweetalert2.all.min.js') }}?ver={{ time() }}"></script>
<script>
    @if(Session::has('success'))
        swal(
            "Success",
            "{{ Session::get('success') }}",
            "success"
        )
    @endif
    @if(Session::has('error'))
        swal(
            "Error!",
            "{{ Session::get('error') }}",
            "error"
        )
    @endif
    @if(Session::has('waning'))
        swal(
            "Warning!",
            "{{ Session::get('waning') }}",
            "warning"
        )
    @endif
</script>
</body>

</html>