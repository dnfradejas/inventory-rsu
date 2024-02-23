
@include('admin.layout.header')
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="/adminlte/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  @include('admin.layout.top-horizontal-nav')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('admin.layout.main-sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
          @yield('content')

          @include('admin.modal.template')
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @include('admin.layout.footer')

  <!-- Control Sidebar -->
  <!--aside class="control-sidebar control-sidebar-dark">
    <-- Control sidebar content goes here>
  </aside -->
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

@include('admin.layout.bottom-scripts')
</body>
</html>
