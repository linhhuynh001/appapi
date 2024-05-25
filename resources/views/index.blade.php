
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<!-- Libs CSS -->
<link href="{{ asset('public/assets/fonts/feather/feather.css' )}}?ver=<?= time() ?>" rel="stylesheet">
<link href="{{ asset('public/assets/fonts/font.css' )}}?ver=<?= time() ?>" rel="stylesheet">
<link href="{{ asset('public/assets/libs/@fortawesome/fontawesome-free/css/alls.min.css' )}}?ver=<?= time() ?>" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('public/assets/css/sweetalert2.min.css' )}}?ver={{ time() }}">
<link rel="stylesheet" href="{{ asset('public/assets/css/dataTables.bootstrap4.min.css' )}}?ver=<?= time() ?>">
<link rel="stylesheet" href="{{ asset('public/assets/croptolljs/cropper.css' )}}?ver=<?= time() ?>"/>
<link rel="stylesheet" href="{{ asset('public/assets/css/bootstrap-tagsinput.css') }}?ver=<?= time() ?>">
<link href="{{ asset('public/assets/css/select2.min.css') }}?ver=<?= time() ?>" rel="stylesheet" />
<script type="text/javascript" src="https://cdn.ckeditor.com/4.5.11/standard/ckeditor.js?ver=<?= time() ?>"></script>
<script src="{{ asset('public/assets/croptolljs/jquery.min.js' )}}?ver=<?= time() ?>"></script>
<script src="{{ asset('public/assets/croptolljs/cropper.js' )}}?ver=<?= time() ?>"></script>
<link rel="stylesheet" href="{{ asset('public/assets/css/theme.min.css' )}}">
    <title>@yield('title')</title>
</head>
<div class="loading-system hide">
    <div class="spinner">
    <div class="rect1"></div>
    <div class="rect2"></div>
    <div class="rect3"></div>
    <div class="rect4"></div>
    <div class="rect5"></div>
    </div>
</div>
<body>
    <style>
        .select2-selection{
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-clip: padding-box;
            background-color: #fff!important;
            border: 1px solid #e8e7ed!important;
            border-radius: 0.25rem;
            color: #000;
            display: block;
            font-size: .875rem;
            font-weight: 400;
            line-height: 1.6;
            padding: 0.5rem 0.75rem;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
            width: 100%;
            height: 48px!important;
        }

        .select2-container{
            width: 100%!important;
        }
    </style>
    <div id="db-wrapper">
        <nav class="navbar-vertical navbar">
            <div class="nav-scroller mt-3">
                <a href="{{ route('dashboard.url') }}" style="padding: 0.5rem 1.5rem;">
                    <span class="going" style="color: #c5b358;text-transform: capitalize;font-size: 25px;">Shoptify</span>
                </a>
                <ul class="navbar-nav flex-column mt-3" id="sideNavbar">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::RouteIs('dashboard.url') ? 'active' : '' }}" href="{{ route('dashboard.url') }}">
                            <div class="reponsive_w"><i class="fas fa-home"></i></div>&ensp;Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ route('get.users') }}">
                            <div class="reponsive_w"><i class="fas fa-clinic-medical"></i></div>&ensp;User
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ route('get.album') }}">
                            <div class="reponsive_w"><i class="fas fa-clinic-medical"></i></div>&ensp;Album
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ route('get.artist') }}">
                            <div class="reponsive_w"><i class="fas fa-clinic-medical"></i></div>&ensp;Artist
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ route('get.audio') }}">
                            <div class="reponsive_w"><i class="fas fa-clinic-medical"></i></div>&ensp;Audio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ route('get.category') }}">
                            <div class="reponsive_w"><i class="fas fa-clinic-medical"></i></div>&ensp;Category
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ route('get.playlist') }}">
                            <div class="reponsive_w"><i class="fas fa-clinic-medical"></i></div>&ensp;Playlist
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- Page Content -->
        <div id="page-content">
            <div class="header">
                <!-- navbar -->
                <nav class="navbar-default navbar navbar-expand-lg">
                    <a id="nav-toggle" href="#">
                        <i class="fe fe-menu"></i>
                    </a>
                    <div class="ms-lg-3 d-block d-md-none d-lg-none mx-2">
                        Shoptify
                    </div>
                    <div class="ms-lg-3 d-none d-md-none d-lg-block">
                        <!-- Form -->
                        <form class="d-flex align-items-center">
                            <span class="position-absolute ps-3 search-icon">
                        <i class="fe fe-search"></i>
                    </span>
                            <input type="search" class="form-control form-control-sm ps-6" placeholder="Search Entire Dashboard" style="width:230px"/>
                        </form>
                    </div>
                    <!--Navbar nav -->
                    <ul class="navbar-nav navbar-right-wrap ms-auto d-flex nav-top-wrap">
                        <li class="dropdown stopevent">
                            <a class="btn btn-light btn-icon rounded-circle indicator indicator-primary text-muted" href="#" role="button" id="dropdownNotification" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fe fe-bell"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg" aria-labelledby="dropdownNotification">
                                <div class=" ">
                                    <div class="border-bottom px-3 pb-3 d-flex justify-content-between align-items-center">
                                        <span class="h4 mb-0">Notifications</span>
                                        <a href="# " class="text-muted">
                                            <span class="align-middle">
                                        <i class="fe fe-settings me-1"></i>
                                    </span>
                                        </a>
                                    </div>
                                    <!-- List group -->
                                    <ul class="list-group list-group-flush notification-list-scroll">
                                        <li class="list-group-item bg-light">
                                            <a href="javascript:void()" class="text-link fw-semi-bold">No new announcements</a>
                                        </li>
                                    </ul>
                                    <div class="border-top px-3 pt-3 pb-0">
                                        <a href="javascript:void()" class="text-link fw-semi-bold">See all Notifications</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown ms-2">
                            <a class="rounded-circle" href="#" role="button" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="avatar avatar-md avatar-indicators avatar-online">
                                    <img alt="avatar" src="https://www.w3schools.com/bootstrap4/img_avatar3.png" class="rounded-circle" />
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                                <div class="dropdown-item">
                                    <div class="d-flex">
                                        <div class="avatar avatar-md avatar-indicators avatar-online">
                                            <img alt="avatar" src="https://www.w3schools.com/bootstrap4/img_avatar3.png" class="rounded-circle" />
                                        </div>
                                        <div class="ms-3 lh-1">
                                            <h5 class="mb-1">{{ Auth::user()->username }}</h5>
                                            <p class="mb-0 text-muted">{{ Auth::user()->email }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <ul class="list-unstyled">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}">
                                            <i class="fe fe-power me-2"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>
            <!-- Page Header -->
            <!-- Container fluid -->
            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </div>
    </div>
<script src="{{ asset('public/assets/libs/jquery-slimscroll/jquery.slimscroll.min.js' )}}?ver=<?= time() ?>"></script>
<script src="{{ asset('public/assets/libs/inputmask/dist/jquery.inputmask.min.js' )}}?ver=<?= time() ?>"></script>
<script src="{{ asset('public/assets/libs/dropzone/dist/min/dropzone.min.js' )}}?ver=<?= time() ?>"></script>
<script src="{{ asset('public/assets/libs/dragula/dist/dragula.min.js')}}?ver=<?= time() ?>"></script>
<script src="{{ asset('public/assets/libs/@popperjs/core/dist/umd/popper.min.js') }}?ver=<?= time() ?>"></script>
<script src="{{ asset('public/assets/libs/tippy.js/dist/tippy-bundle.umd.min.js') }}?ver=<?= time() ?>"></script>
<script src="{{ asset('public/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js' )}}?ver=<?= time() ?>"></script>
<script src="{{ asset('public/assets/libs/datatables.net/js/jquery.dataTables.min.js' )}}?ver=<?= time() ?>"></script>
<script src="{{ asset('public/assets/js/js.min.js' )}}?ver=<?= time() ?>"></script>
<script src="{{ asset('public/assets/js/ckeditor.js') }}?ver=<?= time() ?>"></script>
<script src="{{ asset('public/assets/js/select2.min.js') }}?ver=<?= time() ?>"></script>
<script src="{{ asset('public/assets/js/bootstrap-tagsinput.min.js') }}?ver=<?= time() ?>"></script>
<script src="{{ asset('public/assets/js/sweetalert2.all.min.js') }}?ver={{ time() }}"></script>
<script>
    function onLoad(){
      window.setTimeout("location.reload()",350);
    }
</script>
<script>
    $("#multiple").select2({
        placeholder: "Select event types",
        allowClear: true,
    });
    $("select").select2({
        placeholder: "Select event types",
        allowClear: true,
    });
    </script>
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
<script>
	ClassicEditor
    .create( document.querySelector( '#content' ), {} )
    .then( editor => {window.editor = editor;} )
    .catch( err => {} );
    ClassicEditor
    .create( document.querySelector( '#description' ), {} )
    .then( editor => { window.editor = editor; } )
    .catch( err => {} );
    ClassicEditor
    .create( document.querySelector( '#footer1' ), {} )
    .then( editor => { window.editor = editor; } )
    .catch( err => {} );
</script>
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.12/clipboard.min.js"></script>

<script src="{{ asset('public/assets/js/theme.min.js')}}?ver=<?= time() ?>?ver=<?= time() ?>"></script>
</body>

</html>