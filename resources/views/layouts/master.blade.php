<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>Aptavis Test | {{ $config['title'] }}</title>
  <!-- Favicon-->
  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
  <!-- Bootstrap icons-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
  <!-- Core theme CSS (includes Bootstrap)-->
  <link rel="stylesheet" href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('vendor/fontawesome/css/all.min.css')}}">
  <link rel="stylesheet" href="{{asset('vendor/toastr/toastr.min.css')}}">
  @yield('css')
</head>

<body>
  <!-- Navigation-->
  @include('layouts.partials.navigation')
  <!-- Header-->

  <!-- Section-->
  <section class="py-2">
    <div class="container px-4 px-lg-5 mt-5">
      @yield('content')
    </div>
  </section>

  <!-- Footer-->
  <div class="container">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top fixed-bottom">
      <div class="col-md-4 d-flex align-items-center">
        <a href="/" class="mb-3 me-2 mb-md-0 text-muted text-decoration-none lh-1">
          <svg class="bi" width="30" height="24">
            <use xlink:href="#bootstrap" />
          </svg>
        </a>
        <span class="mb-3 mb-md-0 text-muted">&copy; 2022 Alfin Cahyo Wibisono, Aptavist Test</span>
      </div>

      <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
        <li class="ms-3"><a class="text-muted" href="#"><svg class="bi" width="24" height="24">
              <use xlink:href="#twitter" />
            </svg></a></li>
        <li class="ms-3"><a class="text-muted" href="#"><svg class="bi" width="24" height="24">
              <use xlink:href="#instagram" />
            </svg></a></li>
        <li class="ms-3"><a class="text-muted" href="#"><svg class="bi" width="24" height="24">
              <use xlink:href="#facebook" />
            </svg></a></li>
      </ul>
    </footer>
  </div>
  <!-- Bootstrap core JS-->
  <script src="{{asset('vendor/jquery/jquery-3.6.3.min.js')}}"></script>
  <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('vendor/fontawesome/js/all.min.js')}}"></script>
  <script src="{{asset('vendor/toastr/toastr.min.js')}}"></script>
  @yield('scripts')
</body>

</html>