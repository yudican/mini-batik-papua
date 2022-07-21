<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Mini Batik Papua</title>

  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
  <link rel="icon" href="{{asset('assets/img/icon.ico')}}" type="image/x-icon" />

  <!-- Fonts and icons -->
  <script src="{{asset('assets/js/plugin/webfont/webfont.min.js')}}"></script>
  <script>
    WebFont.load({
        			google: {"families":["Lato:300,400,700,900"]},
        			custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: [`{{asset('assets/css/fonts.min.css')}}`]},
        			active: function() {
        				sessionStorage.fonts = true;
        			}
        		});
  </script>

  <!-- CSS Files -->
  <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/atlantis2.css')}}">

  <!-- Styles -->
  {{--
  <link rel="stylesheet" href="{{ mix('css/app.css') }}"> --}}
  @stack('styles')
  @livewireStyles
  <style>
    .cursor-pointer {
      cursor: pointer;
    }

    .cursor-default {
      cursor: default;
    }

    .absolute {
      position: absolute;
      bottom: 5px;
      left: 5px;
    }

    .table td,
    .table th {
      font-size: 14px;
      border-top-width: 0px;
      border-bottom: 1px solid;
      border-color: #ebedf2 !important;
      padding: 0 10px !important;
      height: 60px;
      vertical-align: middle !important;
    }

    .navbar .navbar-nav .nav-item .nav-link:hover {
      background-color: #fff !important;
      color: black border-radius:5px
    }

    .navbar .navbar-nav .nav-item {
      margin-right: 0;
    }

    .navbar .navbar-nav .nav-item:hover {
      background-color: #fff !important;
    }

    .btn-default {
      background-color: #fff;
    }

    .main-header[data-background-color="white"] .navbar-nav .nav-item .nav-link:hover,
    .main-header[data-background-color="white"] .navbar-nav .nav-item .nav-link:focus,
    .main-header.fixed[data-background-color="transparent"] .navbar-nav .nav-item .nav-link:hover,
    .main-header.fixed[data-background-color="transparent"] .navbar-nav .nav-item .nav-link:focus {
      background: #fff !important;
    }
  </style>
  <!-- Scripts -->
  {{-- <script src="{{ mix('js/app.js') }}" defer></script> --}}
</head>

<body class="font-sans antialiased" style="background-color: #fff;">
  <div class="wrapper">

    <div class="main-header" data-background-color="purple">
      <div class="nav-top">
        <div class="container d-flex flex-row">
          <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
              <i class="icon-menu"></i>
            </span>
          </button>
          {{-- <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button> --}}
          <!-- Logo Header -->
          <a href="index.html" class="logo d-flex align-items-center">
            <span class="text-white"><b>MINI BATIK PAPUA</b></span>
          </a>
          <!-- End Logo Header -->

          <!-- Navbar Header -->
          <nav class="navbar navbar-header navbar-expand-lg p-0  d-none d-sm-none d-md-block d-lg-block">

            <div class="container-fluid p-0 ">

              <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                @if (Auth::check())
                <li class="nav-item mr-4">
                  <a class="nav-link" href="{{route('cart')}}">
                    <i class="fa fa-shopping-cart"></i>
                    <span class="notification">{{Auth::user()->carts->count()}}</span>
                  </a>
                </li>
                <li class="nav-item mr-4">
                  <a class="nav-link" href="{{route('chat')}}">
                    <i class="fa fa-envelope"></i>
                    {{-- <span class="notification">4</span> --}}
                  </a>
                </li>
                @else
                <li class="nav-item mr-4 ">
                  <a class="nav-link" href="{{route('login')}}">
                    <span>Login/Register</span>
                  </a>
                </li>
                @endif
                {{-- <li class="nav-item mr-4 ">
                  <a class="nav-link" href="{{route('user.panduan')}}">
                    <span>Panduan</span>
                  </a>
                </li> --}}



                @if (Auth::check())
                <li class="nav-item dropdown hidden-caret">
                  <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                    <div class="avatar-sm">
                      <img src="{{auth()->user()->profile_photo_url}}" alt="..." class="avatar-img rounded-circle">
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="scroll-wrapper dropdown-user-scroll scrollbar-outer" style="position: relative;">
                      <div class="dropdown-user-scroll scrollbar-outer scroll-content" style="height: auto; margin-bottom: 0px; margin-right: 0px; max-height: 0px;">
                        <li>
                          <div class="user-box">
                            <div class="avatar-lg"><img src="{{auth()->user()->profile_photo_url}}" alt="image profile" class="avatar-img rounded"></div>
                            <div class="u-text">
                              <h4>{{auth()->user()->name}}</h4>
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="{{route('dashboard')}}">Dashboard</a>
                          <a class="dropdown-item" href="{{route('update-profile')}}">Profile</a>
                          <a class="dropdown-item" href="{{route('order')}}">Transaksi Saya</a>
                          <div class="dropdown-divider"></div>
                          <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a class="dropdown-item" id="notifDropdown" title="Logout" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                                                    this.closest('form').submit();">
                              <i class="fas fa-sign-out-alt pr-3"></i>
                              <span class="menu-title">Logout</span>
                            </a>
                        </li>
                      </div>
                      <div class="scroll-element scroll-x" style="">
                        <div class="scroll-element_outer">
                          <div class="scroll-element_size"></div>
                          <div class="scroll-element_track"></div>
                          <div class="scroll-bar ui-draggable ui-draggable-handle"></div>
                        </div>
                      </div>
                      <div class="scroll-element scroll-y" style="">
                        <div class="scroll-element_outer">
                          <div class="scroll-element_size"></div>
                          <div class="scroll-element_track"></div>
                          <div class="scroll-bar ui-draggable ui-draggable-handle"></div>
                        </div>
                      </div>
                    </div>
                  </ul>
                </li>
                @endif
              </ul>
            </div>
          </nav>
          <!-- End Navbar -->
        </div>
      </div>
      <div class="nav-bottom bg-white">
        <h3 class="title-menu d-flex d-lg-none">
          Menu
          <div class="close-menu"> <i class="flaticon-cross"></i></div>
        </h3>
        <div class="container d-flex flex-row  d-block d-sm-block d-md-none d-lg-none">
          <ul class="nav page-navigation page-navigation-secondary">
            @if (!Auth::check())
            <li class="nav-item active">
              <a class="nav-link" href="{{route('login')}}">
                <i class="fas fa-sign-in-alt pr-3"></i>
                <span class="menu-title">Login</span>
              </a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="{{route('register')}}">
                <i class="fas fa-edit pr-3"></i>
                <span class="menu-title">Register</span>
              </a>
            </li>
            {{-- <li class="nav-item active">
              <a class="nav-link" href="{{route('user.panduan')}}">
                <i class="fas fa-file pr-3"></i>
                <span class="menu-title">Panduan</span>
              </a>
            </li> --}}
            @else
            <li class="nav-item active">
              <a class="nav-link" href="{{route('order')}}">
                <i class="fas fa-file-invoice-dollar pr-3"></i>
                <span class="menu-title">Daftar Transaksi</span>
              </a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="{{route('cart')}}">
                <i class="fas fa-shopping-cart pr-3"></i>
                <span class="menu-title">Keranjang Saya</span>
              </a>
            </li>
            {{-- <li class="nav-item active">
              <a class="nav-link" href="{{route('user.panduan')}}">
                <i class="fas fa-file pr-3"></i>
                <span class="menu-title">Panduan</span>
              </a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="{{route('logout')}}">
                <i class="fas fa-sign-out-alt pr-3"></i>
                <span class="menu-title">Logout</span>
              </a>
            </li> --}}
            @endif
          </ul>
        </div>
      </div>
    </div>

    <div class="main-panel">
      <div class="container">{{$slot}}</div>
    </div>
    <footer class="footer">
      <div class="container">
        <div class="copyright ml-auto">
          Mini Batik Papua {{date('Y')}}
        </div>
      </div>
    </footer>
  </div>


  <script src="{{ asset('assets/js/core/jquery.3.2.1.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

  <!-- jQuery UI -->
  <script src="{{ asset('assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js') }}"></script>


  <!-- jQuery Scrollbar -->
  <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/atlantis2.min.js') }}"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  @stack('scripts')
  <script>
    document.addEventListener('livewire:load', function(e) {
      window.livewire.on('showAlert', ({msg, redirect=false, path='/'}) => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: msg,
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: false
                        })

                        if (redirect) {
                            setTimeout(() => {
                                window.location.href=path
                            }, 3000);
                        }
                    });
                    
                    window.livewire.on('showAlertError', (data) => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.msg,
                            timer: 2000,
                            showCancelButton: false,
                            showConfirmButton: false
                        })
                    });
                })
  </script>
  @livewireScripts
</body>

</html>